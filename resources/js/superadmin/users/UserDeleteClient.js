const AlertMessageHandler = require('../../helpers/AlertMessageHandler');

function UserDeleteClient() {
    this.oHoldonOpts = {
        theme: 'sk-circle'
    };

    this.amhAlertHandler = new AlertMessageHandler();
    this.amhAlertHandler.init('#requisigner-main-alert-box');

    this.elUserRecordTableBody = jQuery('#requisigner-user-list > tbody');
    this.elUserRecordTableBody.click(this.handleControl.bind(this));

    this.elBSModal = jQuery('#requisigner-delete-confirm');
    this.bsDeleteConfirm = new bootstrap.Modal(this.elBSModal.get(0));
    this.elDeleteConfirmControls = this.elBSModal.find('.modal-footer > button');

    this.sCSRFToken = jQuery('meta[name="csrf_token"]').attr('content');
}

UserDeleteClient.prototype.deleteUser = function(user_id) {
    let boolConfirmed = false;

    this.elDeleteConfirmControls.click((event) => {
        let elClickedBtn = jQuery(event.target);

        if (elClickedBtn.text() == 'Yes') {
            boolConfirmed = true;
        }

        this.elDeleteConfirmControls.unbind();
        this.bsDeleteConfirm.hide();
    });

    this.elBSModal.on('hidden.bs.modal', (event) => {
        if (boolConfirmed) {
            this.oHoldonOpts.message = 'Deleting user...';
            HoldOn.open(this.oHoldonOpts);

            jQuery.ajax({
                type: 'POST',
                url: REQUISIGNER_DELETE_USER_URL,
                data: JSON.stringify({ "user_id" : user_id }),
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

    this.bsDeleteConfirm.show();
}

UserDeleteClient.prototype.handleControl = function(event) {
    let elSelectedItem = jQuery(event.target);

    if (elSelectedItem.data('control') != undefined) {
        switch (elSelectedItem.data('control')) {
            case 'delete':
                this.deleteUser(elSelectedItem.data('userId'));
                break;
            default:
                return;
        }
    }
}

jQuery(document).ready(() => {
    let uccUserHandler = new UserDeleteClient();
});