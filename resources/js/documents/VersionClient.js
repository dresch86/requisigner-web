const AlertMessageHandler = require('../helpers/AlertMessageHandler');

function VersionClient() {
    this.amhAlertHandler = new AlertMessageHandler();
    this.amhAlertHandler.init('#requisigner-main-alert-box');

    this.oHoldonOpts = {
        theme: 'sk-circle'
    };

    this.elVersionForm = jQuery('#requisigner-template-version-form');
    this.elVersionForm.submit(this.updateVersion.bind(this));

    let i = 1;
    this.elVersionForm.find('select.requisigner-placeholder-order').each((index, orderMenu) => {
        orderMenu.value = i;
        ++i;
    });

    this.sCSRFToken = jQuery('meta[name="csrf_token"]').attr('content');
    this.elEnforceSigOrder = jQuery('#requisigner-enforce-sig-order');
}

VersionClient.prototype.updateVersion = function(event) {
    event.preventDefault();

    let aOrders = [];
    let oVersionFields = {
        placeholders : [],
        enforce_order : this.elEnforceSigOrder.is(":checked")
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
    let vcVersionFormHandler = new VersionClient();
});