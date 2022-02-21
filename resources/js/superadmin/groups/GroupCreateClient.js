const AlertMessageHandler = require('../../helpers/AlertMessageHandler');

function GroupCreateClient() {
    this.elGroupNameInput = jQuery('#requisigner-group-name');
    this.elGroupCreateForm = jQuery('#requisigner-create-group-form');

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

    this.qjGroupDescr = new Quill('#requisigner-group-description', this.oQuillOps);

    this.oHoldonOpts = {
        theme: 'sk-circle'
    };

    this.amhAlertHandler = new AlertMessageHandler();
    this.amhAlertHandler.init('#requisigner-main-alert-box');

    this.elGroupCreateForm.submit(this.postGroup.bind(this));
}

GroupCreateClient.prototype.postGroup = function(event) {
    event.preventDefault();

    let fdFormData = new FormData(this.elGroupCreateForm.get(0));
    fdFormData.append('group_description', this.qjGroupDescr.root.innerHTML);

    let sGroupName = fdFormData.get('group_name');
    sGroupName = sGroupName.trim();

    if (sGroupName.length < 3) {
        this.amhAlertHandler.showAlert('danger', 'Group name must be at least 3 characters!');
        return;
    }

    this.oHoldonOpts.message = 'Creating group...';
    HoldOn.open(this.oHoldonOpts);

    jQuery.ajax({
        type: 'POST',
        url: this.elGroupCreateForm.attr('action'),
        data: fdFormData,
        processData: false,
        contentType: false
    }).done(response => {
        if (response.code == 200) {
            this.amhAlertHandler.showAlert('success', response.result);
            this.elGroupCreateForm.get(0).reset();
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
    let gccGroupHandler = new GroupCreateClient();
});