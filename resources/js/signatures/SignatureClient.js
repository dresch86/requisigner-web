const AlertMessageHandler = require('../helpers/AlertMessageHandler');

function SignatureClient() {
    this.elSigPadCanvas = jQuery('#requisigner-signature-pad');
    this.elSigPadCanvas.attr('width', '340');
    this.elSigPadCanvas.attr('height', '100');

    this.spRegFormSigPad = new SignaturePad(this.elSigPadCanvas.get(0));
    
    this.elSigClearBtn = jQuery('#requisigner-vsig-clear-btn');
    this.elSigClearBtn.click(() => {
        this.spRegFormSigPad.clear();
    });

    this.spSodiumHandler = window.sodium;
    this.generateKeypair();
}

SignatureClient.prototype.generateKeypair = async function() {
    let random = await this.spSodiumHandler.randombytes_buf(32);
    let hash = await this.spSodiumHandler.crypto_generichash('hello world');
    console.log({
        'random': random.toString('hex'),
        'hash': hash.toString('hex')
    });
}

jQuery(document).ready(() => {
    (async function() {
        if (!window.sodium) {
            window.sodium = await SodiumPlus.auto();
        }

        let scSignatureHandler = new SignatureClient();
    })();
});