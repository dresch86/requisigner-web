const AlertMessageHandler = require('../helpers/AlertMessageHandler');

function TemaplatesListClient() {
    this.sCSRFToken = jQuery('meta[name="csrf_token"]').attr('content');

    this.oHoldonOpts = {
        theme: 'sk-circle'
    };

    this.amhAlertHandler = new AlertMessageHandler();
    this.amhAlertHandler.init('#requisigner-main-alert-box');

    this.elTemplateEntries = jQuery('#requisigner-templates-list > tbody > tr');
    this.elTemplateEntries.click(this.handleControl.bind(this));

    this.elBSModal = jQuery('#requisigner-modal');
    this.bsModalWindow = new bootstrap.Modal(this.elBSModal.get(0));

    this.elModalTitle = this.elBSModal.find('.modal-title');
    this.elModalBody = this.elBSModal.find('.modal-body');
    this.elModalFooter = this.elBSModal.find('.modal-footer');

    this.elYesBtn = document.createElement('button');
    this.elYesBtn.setAttribute('type', 'button');
    this.elYesBtn.classList.add('btn', 'btn-primary');
    this.elYesBtn.innerHTML = 'Yes';

    this.elNoBtn = document.createElement('button');
    this.elNoBtn.setAttribute('type', 'button');
    this.elNoBtn.classList.add('btn', 'btn-secondary');
    this.elNoBtn.dataset.bsDismiss = "modal";
    this.elNoBtn.innerHTML = 'No';

    this.elCloseBtn = document.createElement('button');
    this.elCloseBtn.setAttribute('type', 'button');
    this.elCloseBtn.classList.add('btn', 'btn-secondary');
    this.elCloseBtn.dataset.bsDismiss = "modal";
    this.elCloseBtn.innerHTML = 'Close';
}

TemaplatesListClient.prototype.buildDescriptionModal = function(description) {
    this.elModalTitle.text('Description');

    this.elModalBody.empty();
    this.elModalBody.append(description);
    
    this.elModalFooter.empty();
    this.elModalFooter.append(this.elCloseBtn);

    jQuery(this.elCloseBtn).click((event) => {
        this.bsModalWindow.hide();
        jQuery(event.target).unbind();
    });

    this.bsModalWindow.show();
}

TemaplatesListClient.prototype.deleteTemplate = function(template_id) {
    this.elModalTitle.text('Delete Confirm');

    this.elModalBody.empty();
    this.elModalBody.html('Are you sure you want to delete this template?');

    this.elModalFooter.empty();
    this.elModalFooter.append([this.elYesBtn, this.elNoBtn]);

    let boolConfirmed = false;

    jQuery(this.elYesBtn).click((event) => {
        boolConfirmed = true;
        this.bsModalWindow.hide();
        jQuery(event.target).unbind();
    });

    jQuery(this.elNoBtn).click((event) => {
        this.bsModalWindow.hide();
        jQuery(event.target).unbind();
    });

    this.elBSModal.on('hidden.bs.modal', (event) => {
        if (boolConfirmed) {
            this.oHoldonOpts.message = 'Deleting template...';
            HoldOn.open(this.oHoldonOpts);

            jQuery.ajax({
                type: 'POST',
                url: REQUISIGNER_DELETE_TEMPLATE_URL,
                data: JSON.stringify({ "template_id" : template_id }),
                contentType: 'application/json',
                processData: false,
                headers: {
                    'X-CSRF-TOKEN' : this.sCSRFToken
                }
            }).done(response => {
                if (response.code == 200) {
                    this.amhAlertHandler.showAlert('success', response.result, true);
                } else {
                    this.amhAlertHandler.showAlert('danger', response.result);
                }
            }).always(() => {
                HoldOn.close();
            });
        }

        this.elBSModal.unbind();
    });

    this.bsModalWindow.show();
}

TemaplatesListClient.prototype.handleControl = function(event) {
    let elSelectedItem = jQuery(event.target);

    if (elSelectedItem.data('control') != undefined) {
        let elTemplateRow = jQuery(event.currentTarget);

        switch (elSelectedItem.data('control')) {
            case 'delete':
                this.deleteTemplate(elTemplateRow.data('templateId'));
                break;
            case 'show_description':
                this.buildDescriptionModal(elSelectedItem.next().children().clone());
                break;
            default:
                return;
        }
    }
}

jQuery(document).ready(() => {
    let tvcTemplateViewHandler = new TemaplatesListClient();
});