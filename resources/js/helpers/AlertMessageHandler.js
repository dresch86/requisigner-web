function AlertMessageHandler() {
    this.elTimeoutInProgress = false;
}

AlertMessageHandler.prototype.showAlert = function(type, message, refresh, no_timeout) {
    let sMessage = ((message === undefined) || (message === null)) ? '' : message.trim();
    let sType = ((message === undefined) || (type === null)) ? 'danger' : type;

    if (sMessage.length == 0) {
        sMessage = 'A critical system error occurred! Please try again later!';
    }
    
    this.elResponseTxt.html(sMessage);
    this.elResponseBox.addClass('d-flex');
    this.elResponseBox.removeClass('d-none');

    if (sType == 'danger') {
        this.elResponseBox.addClass('alert-danger');
    } else if (sType == 'success') {
        this.elResponseBox.addClass('alert-success');
    } else if (sType == 'warning') {
        this.elResponseBox.addClass('alert-warning');
    } else {
        this.elResponseBox.addClass('alert-info');
    }

    jQuery('html, body').animate({
        scrollTop: (this.elResponseBox.offset().top - (this.elResponseBox.outerHeight(true) / 2))
    }, 500);

    if (no_timeout !== true) {
        this.elTimeoutInProgress = true;
        setTimeout(() => {
            if (this.elTimeoutInProgress) {
                this.hideAlert();
            } 
    
            if (refresh === true) {
                window.location.reload();
            }
        }, 5000);
    }
}

AlertMessageHandler.prototype.hideAlert = function() {
    this.elResponseBox.addClass('d-none');
    this.elResponseBox.removeClass('d-flex');
    this.elResponseBox.removeClass('alert-info');
    this.elResponseBox.removeClass('alert-danger');
    this.elResponseBox.removeClass('alert-success');
    this.elResponseBox.removeClass('alert-warning');
    this.elResponseTxt.empty();

    this.elTimeoutInProgress = false;
}

AlertMessageHandler.prototype.init = function(alertBoxId) {
    this.elResponseBox = jQuery(alertBoxId);

    if (this.elResponseBox.length != 1) {
        throw new Error('[AlertMessageHandler] - Alert box id invalid');
    }

    this.elResponseTxt = this.elResponseBox.find('> .alert-text');

    if (this.elResponseTxt.length != 1) {
        throw new Error('[AlertMessageHandler] - Alert text box invalid');
    }

    this.elDismissBtn = this.elResponseBox.find('> button.alert-dismiss-btn');

    if (this.elDismissBtn.length != 1) {
        throw new Error('[AlertMessageHandler] - Alert dismiss button not found');
    }

    this.elDismissBtn.click(this.hideAlert.bind(this));
}

module.exports = AlertMessageHandler;