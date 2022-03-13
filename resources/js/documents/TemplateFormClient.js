const AlertMessageHandler = require('../helpers/AlertMessageHandler');

function TemplateFormClient() {
    this.amhAlertHandler = new AlertMessageHandler();
    this.amhAlertHandler.init('#requisigner-main-alert-box');

    this.amhDialogAlertHandler = new AlertMessageHandler();
    this.amhDialogAlertHandler.init('#requisigner-dialog-alert-box');

    this.oHoldonOpts = {
        theme: 'sk-circle'
    };

    this.elBSModal = jQuery('#requisigner-modal');
    this.elModalBody = this.elBSModal.find('.modal-body');
    this.bsModalWindow = new bootstrap.Modal(this.elBSModal.get(0));

    this.elDocumentAssignForm = jQuery('#requisigner-document-assign-form');
    this.elDocumentAssignForm.submit(this.postDocument.bind(this));
    this.sCSRFToken = this.elDocumentAssignForm.find('> input[name="_token"]').val();

    this.elPlaceholderList = jQuery('#requisigner-placeholder-list');    
    this.elPlaceholderInputs = this.elPlaceholderList.find('> tbody > tr > td:last-child > input');
    this.elPlaceholderInputs.keyup(this.querySignees.bind(this));

    jQuery('button.requisigner-btn-done').click(this.collectUsers.bind(this));
    jQuery('button.requisigner-btn-sigs').click(() => {
        this.bsModalWindow.show();
    });

    this.aSignees = [];
    this.elPDFViewer = jQuery('#requisigner-pdf-viewer');
    this.elDocTitle = this.elDocumentAssignForm.find('input[id="requisigner-document-title"]');
    this.elDocDueDate = this.elDocumentAssignForm.find('input[id="requisigner-document-due-date"]');
    this.elDocMetatags = this.elDocumentAssignForm.find('input[id="requisigner-document-metatags"]');

    flatpickr(this.elDocDueDate, {
        enableTime: true,
        noCalendar: false,
        dateFormat: 'm-d-Y h:i K'
    });

    jQuery('.requisigner-btn-save').click(() => {
        this.elDocumentAssignForm.submit();
    });
}

TemplateFormClient.prototype.querySignees = function(event) {
    let elOption = null;
    let elSigneeList = null;

    let elInput = jQuery(event.target);
    let sQueryUser = elInput.val().trim();

    if (sQueryUser.length > 3) {
        let fdFormData = new FormData();
        fdFormData.append('query_name', sQueryUser);

        jQuery.ajax({
            type: 'POST',
            url: REQUISIGNER_USER_SEARCH_URL,
            data: fdFormData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN' : this.sCSRFToken
            }
        }).done(response => {
            elSigneeList = elInput.find('+ datalist.requisigner-signees-list');
            elSigneeList.empty();

            if (response.code == 200) {
                for (let i=0; i < response.result.length; i++) {
                    elOption = jQuery(document.createElement('option'));
                    elOption.data('uid', response.result[i][0]);
                    elOption.attr('value', response.result[i][1]);
                    elSigneeList.append(elOption);
                }
            } else {
                elOption = jQuery(document.createElement('option'));
                elOption.data('uid', -1);
                elOption.attr('value', 'Search error...');
                elSigneeList.append(elOption);
            }
        });
    } else {
        elOption = jQuery(document.createElement('option'));
        elOption.data('uid', -1);
        elOption.attr('value', 'Enter name above...');

        elSigneeList = elInput.find('+ datalist.requisigner-signees-list');
        elSigneeList.empty();
        elSigneeList.append(elOption);
    }
}

TemplateFormClient.prototype.collectUsers = function(event) {
    let elInput = null;
    let elSignee = null;
    let iInvalidSignee = 0;
    let aSignatureAssignments = [];

    this.elPlaceholderInputs.each((index, element) => {
        elInput = jQuery(element);
        elSignee = elInput.find('+ datalist.requisigner-signees-list > option[value="' + elInput.val().trim() + '"]');

        if (elSignee.length > 0) {
            aSignatureAssignments.push({
                user_id : elSignee.data('uid'),
                placeholder : elInput.data('placeholderId')
            });
        } else {
            ++iInvalidSignee;
        }
    });

    if (iInvalidSignee == 0) {
        this.aSignees = aSignatureAssignments;
        this.bsModalWindow.hide();
    } else {
        this.amhDialogAlertHandler.showAlert('danger', 'One or more of the signees you entered was not found in our system!');
    }
}

TemplateFormClient.prototype.postDocument = function(event) {
    event.preventDefault();

    if (this.aSignees.length > 0) {
        this.elPDFViewer[0].contentWindow.PDFViewerApplication
        .getPDFBytes()
        .then(pdfBytes => {
            let frPDFByteReader = new FileReader();
            frPDFByteReader.readAsDataURL(pdfBytes);

            frPDFByteReader.onload = () => {
                let oDocument = {
                    version_id : this.elPDFViewer.data('versionId'),
                    title : this.elDocTitle.val(),
                    due_date : this.elDocDueDate.val(),
                    metatags : this.elDocMetatags.val().trim().split(/,\s*/),
                    signees : this.aSignees,
                    pdf_document : frPDFByteReader.result.replace(/^data:.+;base64,/, '')
                };

                this.oHoldonOpts.message = 'Submitting document...';
                HoldOn.open(this.oHoldonOpts);
        
                jQuery.ajax({
                    type: 'POST',
                    url: this.elDocumentAssignForm.attr('action'),
                    data: JSON.stringify(oDocument),
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
            };
        });
    } else {
        this.amhAlertHandler.showAlert('danger', 'No signees were added! Please check and correct.');
    }
}

jQuery(document).ready(() => {
    let tfcTemplateFormHandler = new TemplateFormClient();
});