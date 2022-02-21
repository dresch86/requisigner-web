const AlertMessageHandler = require('../../helpers/AlertMessageHandler');

function UserCreateClient() {
    this.oHoldonOpts = {
        theme: 'sk-circle'
    };

    this.amhAlertHandler = new AlertMessageHandler();
    this.amhAlertHandler.init('#requisigner-main-alert-box');

    this.elUserCreateForm = jQuery('#requisigner-create-user-form');
    this.elUserCreateForm.submit(this.postUser.bind(this));
}

UserCreateClient.prototype.postUser = function(event) {
    event.preventDefault();

    let fdFormData = new FormData(this.elUserCreateForm.get(0));

    if (fdFormData.get('username').length < 3) {
        this.amhAlertHandler.showAlert('danger', 'Username must be at least 3 characters!');
        return;
    }

    if (fdFormData.get('password').length < 6) {
        this.amhAlertHandler.showAlert('danger', 'Password must be at least 6 characters!');
        return;
    }

    let sHumanName = fdFormData.get('human_name');
    sHumanName = sHumanName.trim();

    if (sHumanName.length == 0) {
        this.amhAlertHandler.showAlert('danger', 'You must enter a human name for this user!');
        return;
    } else {
        fdFormData.set('human_name', sHumanName);
    }

    this.oHoldonOpts.message = 'Creating user...';
    HoldOn.open(this.oHoldonOpts);

    jQuery.ajax({
        type: 'POST',
        url: this.elUserCreateForm.attr('action'),
        data: fdFormData,
        processData: false,
        contentType: false
    }).done(response => {
        if (response.code == 200) {
            this.amhAlertHandler.showAlert('success', response.result);
            this.elUserCreateForm.get(0).reset();
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
}

jQuery(document).ready(() => {
    let uccUserHandler = new UserCreateClient();
});