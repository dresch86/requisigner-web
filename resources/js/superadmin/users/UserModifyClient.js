const AlertMessageHandler = require('../../helpers/AlertMessageHandler');

function UserModifyClient() {
    this.amhAlertHandler = new AlertMessageHandler();
    this.amhAlertHandler.init('#requisigner-main-alert-box');

    this.elUserModifyForm = jQuery('#requisigner-modify-user-form');
    this.elUserModifyForm.submit(this.modifyUser.bind(this));

    this.reRFCEmail = /^((([!#$%&'*+\-/=?^_`{|}~\w])|([!#$%&'*+\-/=?^_`{|}~\w][!#$%&'*+\-/=?^_`{|}~\.\w]{0,}[!#$%&'*+\-/=?^_`{|}~\w]))[@]\w+([-.]\w+)*\.\w+([-.]\w+)*)$/;
    this.oHoldonOpts = {
        theme: 'sk-circle'
    };
}

UserModifyClient.prototype.modifyUser = function(event) {
    event.preventDefault();

    let fdFormData = new FormData(this.elUserModifyForm.get(0));
    let sEmail = fdFormData.get('email').trim();
    let sName = fdFormData.get('human_name').trim();
    let sUsername = fdFormData.get('username').trim();
    let sPassword = fdFormData.get('password').trim();
    let sPasswordConfirm = fdFormData.get('password_confirm').trim();

    if (sUsername.length < 3) {
        this.amhAlertHandler.showAlert('danger', 'Username must be at least 3 characters!');
        return;
    }

    if (sPassword.length > 0) {
        if (sPassword.length < 6) {
            this.amhAlertHandler.showAlert('danger', 'Password must be at least 6 characters!');
            return;
        } else if (sPassword != sPasswordConfirm) {
            this.amhAlertHandler.showAlert('danger', 'Passwords do not match!');
            return;
        }
    }

    if (sName.length == 0) {
        this.amhAlertHandler.showAlert('danger', 'You must enter a human name for this user!');
        return;
    } else {
        fdFormData.set('human_name', sName);
    }

    if (!this.reRFCEmail.test(sEmail)) {
        this.amhAlertHandler.showAlert('danger', 'Your email address contains invalid characters!');
        return;
    }

    if (!fdFormData.has('user_suspended')) {
        fdFormData.append('user_suspended', 0);
    }

    this.oHoldonOpts.message = 'Modifying user...';
    HoldOn.open(this.oHoldonOpts);

    jQuery.ajax({
        type: 'POST',
        url: this.elUserModifyForm.attr('action'),
        data: fdFormData,
        processData: false,
        contentType: false
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
}

jQuery(document).ready(() => {
    let umcUserHandler = new UserModifyClient();
});