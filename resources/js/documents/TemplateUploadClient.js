const AlertMessageHandler = require('../helpers/AlertMessageHandler');

function TemplateUploadClient() {
    this.elDocUploadName = jQuery('#requisigner-document-name');
    this.elDocUploadInput = jQuery('#requisigner-document-upload');
    this.elDocUploadForm = jQuery('#requisigner-document-upload-form');

    this.elDocUploadInput.filepond();
    this.elDocUploadInput.filepond('storeAsFile', true);
    this.elDocUploadInput.filepond('allowMultiple', false);
    this.elDocUploadInput.filepond('acceptedFileTypes', ['application/pdf']);
    this.elDocUploadInput.filepond('maxFileSize', REQUISIGNER_UPLOAD_MAX_SIZE);

    this.oQuillOps = {
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'align': [] }],
                [{ 'size': ['small', false, 'large', 'huge'] }],
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['link', 'image', 'blockquote'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['clean']
            ]
        },
        placeholder: 'Enter description here (optional)...',
        theme: 'snow'
    };

    this.qjsDocumentDescr = new Quill('#requisigner-document-description', this.oQuillOps);

    this.oHoldonOpts = {
        theme: 'sk-circle'
    };

    this.amhAlertHandler = new AlertMessageHandler();
    this.amhAlertHandler.init('#requisigner-main-alert-box');

    this.elDocUploadForm.submit(this.postDocument.bind(this));
}

TemplateUploadClient.prototype.postDocument = function(event) {
    event.preventDefault();

    let sDocName = this.elDocUploadName.val().trim();
    let fdFormData = new FormData(this.elDocUploadForm.get(0));
    fdFormData.append('document_description', this.qjsDocumentDescr.root.innerHTML);

    if (sDocName.length < 3) {
        this.amhAlertHandler.showAlert('danger', 'Document name must be at least 3 characters!');
        return;
    }

    if (fdFormData.get('document_file').length == 0) {
        this.amhAlertHandler.showAlert('danger', 'You must select a PDF file to upload!');
        return;
    }

    if (!fdFormData.has('group_read')) {
        fdFormData.append('group_read', 0);
    }

    if (!fdFormData.has('group_edit')) {
        fdFormData.append('group_edit', 0);
    }

    if (!fdFormData.has('subgroup_read')) {
        fdFormData.append('subgroup_read', 0);
    }

    if (!fdFormData.has('subgroup_edit')) {
        fdFormData.append('subgroup_edit', 0);
    }

    this.elDocUploadName.val(sDocName);
    this.oHoldonOpts.message = 'Uploading to library...';

    HoldOn.open(this.oHoldonOpts);

    jQuery.ajax({
        type: 'POST',
        url: this.elDocUploadForm.attr('action'),
        data: fdFormData,
        processData: false,
        contentType: false
    }).done(response => {
        if (response.code == 200) {
            // Redirect to configure version placeholders and signing preferences
            window.location.href = response.result;
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
    // Needed for checking file upload size
    FilePond.registerPlugin(FilePondPluginFileValidateSize);

    // Needed for checking file extension
    FilePond.registerPlugin(FilePondPluginFileValidateType);

    let cacAttachmentsHandler = new TemplateUploadClient();
});