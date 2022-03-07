const AlertMessageHandler = require('../helpers/AlertMessageHandler');

function ProfileClient() {
    this.amhAlertHandler = new AlertMessageHandler();
    this.amhAlertHandler.init('#requisigner-main-alert-box');

    this.elProfileForm = jQuery('#requisigner-user-profile');
    this.elProfileForm.submit(this.saveProfile.bind(this));

    this.reRFCEmail = /^((([!#$%&'*+\-/=?^_`{|}~\w])|([!#$%&'*+\-/=?^_`{|}~\w][!#$%&'*+\-/=?^_`{|}~\.\w]{0,}[!#$%&'*+\-/=?^_`{|}~\w]))[@]\w+([-.]\w+)*\.\w+([-.]\w+)*)$/;
    this.oHoldonOpts = {
        theme: 'sk-circle'
    };
}

ProfileClient.prototype.saveProfile = function(event) {
    event.preventDefault();

    let aErrors = [];
    let fdFormData = new FormData(this.elProfileForm.get(0));

    let sEmail = fdFormData.get('email').trim();
    let sName = fdFormData.get('human_name').trim();
    let sPassword = fdFormData.get('password').trim();
    let sPasswordConfirm = fdFormData.get('password_confirm').trim();

    if (sName.length == 0) {
        aErrors.push('Your name is a required field!');
    }

    if (!this.reRFCEmail.test(sEmail)) {
        aErrors.push('Your email address contains invalid characters!');
    }

    if (sPassword.length > 0) {
        if (sPassword != sPasswordConfirm) {
            aErrors.push('Passwords do not match!');
        }
    }

    this.oHoldonOpts.message = 'Saving Profile...';
    HoldOn.open(this.oHoldonOpts);

    jQuery.ajax({
        type: 'POST',
        url: this.elProfileForm.attr('action'),
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
    let pcProfileHandler = new ProfileClient();
});