const AlertMessageHandler = require('../helpers/AlertMessageHandler');

function TemplateFormClient() {
    this.amhAlertHandler = new AlertMessageHandler();
    this.amhAlertHandler.init('#requisigner-main-alert-box');

    this.oHoldonOpts = {
        theme: 'sk-circle'
    };

    this.elBSModal = jQuery('#requisigner-modal');
    this.bsModalWindow = new bootstrap.Modal(this.elBSModal.get(0));

    this.elModalBody = this.elBSModal.find('.modal-body');
    this.sCSRFToken = jQuery('meta[name="csrf_token"]').attr('content');

    this.elSigneeList = jQuery('#requisigner_signees_list');
    this.elPlaceholderList = jQuery('#requisigner-placeholder-list');
    this.elPlaceholderList.find('> tbody > tr > td:last-child > input').keyup(this.querySignees.bind(this));

    jQuery('button.requisigner-btn-save').click(this.postDocument.bind(this));
    jQuery('button.requisigner-btn-done').click(this.collectUsers.bind(this));
    jQuery('button.requisigner-btn-sigs').click(() => {
        this.bsModalWindow.show();
    });
}

TemplateFormClient.prototype.querySignees = function(event) {
    let sQuery = jQuery(event.target).val().trim();

    if (sQuery.length > 3) {
        let fdFormData = new FormData();
        fdFormData.append('query_name', sQuery);

        jQuery.ajax({
            type: 'POST',
            url: REQUISIGNER_USER_SEARCH_URL,
            data: fdFormData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN' : this.sCSRFToken
            }
        }).done(response => {
            if (response.code == 200) {
                let elOption = null;
                this.elSigneeList.empty();

                for (let i=0; i < response.result.length; i++) {
                    elOption = jQuery(document.createElement('option'));
                    elOption.data('uid', response.result[i][0]);
                    elOption.attr('value', response.result[i][1]);
                    this.elSigneeList.append(elOption);
                }
            } else {
                
            }
        });
    }
}

TemplateFormClient.prototype.collectUsers = function(event) {
    this.bsModalWindow.hide();
}

TemplateFormClient.prototype.postDocument = function(event) {
    let aOrders = [];
    let oVersionFields = {
        placeholders : [],
        enforce_order : this.elEnforceSigOrder.is(':checked')
    };

    let elPlaceholders = this.elVersionForm.find('> table > tbody > tr');
    elPlaceholders.each((index, element) => {
        let elPlaceholder = jQuery(element);
        let iOrder = elPlaceholder.find('select.requisigner-placeholder-order').val();
        let sFriendlyName = elPlaceholder.find('input.requisigner-ph-friendly-name').val();

        if (aOrders.indexOf(iOrder) == -1) {
            oVersionFields.placeholders.push({
                id : elPlaceholder.data('placeholderId'),
                friendly_name : sFriendlyName,
                order : iOrder
            });
    
            aOrders.push(iOrder);
        } 
    });

    if (oVersionFields.placeholders.length == elPlaceholders.length) {
        this.oHoldonOpts.message = 'Updating template version...';
        HoldOn.open(this.oHoldonOpts);
    
        jQuery.ajax({
            type: 'POST',
            url: this.elVersionForm.attr('action'),
            data: JSON.stringify(oVersionFields),
            contentType: 'application/json',
            processData: false,
            headers: {
                'X-CSRF-TOKEN' : this.sCSRFToken
            }
        }).done(response => {
            if (response.code == 200) {
                this.amhAlertHandler.showAlert('success', response.result);
            } else {
                if (Array.isArray(response.result)) {
                    this.amhAlertHandler.showAlert('danger', response.result.join('<br>'));
                } else {
                    this.amhAlertHandler.showAlert('danger', response.result);
                }
            }
        }).always(() => {
            HoldOn.close();
        });
    } else {
        this.amhAlertHandler.showAlert('danger', 'Please make sure a unique order is assigned to each placeholder!');
    }
}

jQuery(document).ready(() => {
    let tfcTemplateFormHandler = new TemplateFormClient();
});