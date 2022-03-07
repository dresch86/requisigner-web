const AlertMessageHandler = require('../helpers/AlertMessageHandler');

function SignatureClient() {
    this.amhAlertHandler = new AlertMessageHandler();
    this.amhAlertHandler.init('#requisigner-main-alert-box');

    this.elSigPadCanvas = jQuery('#requisigner-signature-pad');
    this.elSigPadCanvas.attr('width', '340');
    this.elSigPadCanvas.attr('height', '100');

    this.sCSRFToken = jQuery('meta[name="csrf_token"]').attr('content');
    this.spVisibleSigPad = new SignaturePad(this.elSigPadCanvas.get(0));
    
    this.elSigClearBtn = jQuery('#requisigner-vsig-clear-btn');
    this.elSigClearBtn.click(() => {
        this.spVisibleSigPad.clear();
    });
    
    this.elVisSigSaveBtn = jQuery('#requisigner-vsig-save-btn');
    this.elVisSigSaveBtn.click(this.saveVisibleSig.bind(this));

    this.spSodiumHandler = window.sodium;
    this.generateKeypair();
}

SignatureClient.prototype.saveVisibleSig = function() {
    if (!this.spVisibleSigPad.isEmpty()) {
        HoldOn.open({
            theme: "sk-cube-grid",
            message: "Saving Visible Signature..."
        });

        jQuery.ajax({
            type: 'POST',
            url: REQUISIGNER_VSIG_HANDLER_URL,
            data: JSON.stringify({
                "image" : this.spVisibleSigPad.toDataURL(), 
                "controlPoints" : this.spVisibleSigPad.toData()
            }),
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
        this.amhAlertHandler.showAlert('danger', 'No signature drawn!');
    }
}

SignatureClient.prototype.generateKeypair = async function() {
    let random = await this.spSodiumHandler.randombytes_buf(32);
    let hash = await this.spSodiumHandler.crypto_generichash('hello world');
    console.log({
        'random': random.toString('hex'),
        'hash': hash.toString('hex')
    });
}

SignatureClient.prototype.initSigPad = function() {
    jQuery.ajax({
        type: 'GET',
        url: REQUISIGNER_VSIG_CPTS_URL
    }).done(response => {
        if (response.code == 200) {
            if ((response.result.length > 0) && Array.isArray(response.result)) {
                this.spVisibleSigPad.fromData(response.result);
            }
        } else {
            this.amhAlertHandler.showAlert('danger', 'Failed to load existing visible signature!');
        }
    });
}

jQuery(document).ready(() => {
    (async function() {
        if (!window.sodium) {
            window.sodium = await SodiumPlus.auto();
        }

        let scSignatureHandler = new SignatureClient();
        scSignatureHandler.initSigPad();
    })();
});