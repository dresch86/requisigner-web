(()=>{var e={1838:e=>{function AlertMessageHandler(){this.elTimeoutInProgress=!1}AlertMessageHandler.prototype.showAlert=function(e,t,s,o){var l=this,r=null==t?"":t.trim(),i=void 0===t||null===e?"danger":e;0==r.length&&(r="A critical system error occurred! Please try again later!"),this.elResponseTxt.html(r),this.elResponseBox.addClass("d-flex"),this.elResponseBox.removeClass("d-none"),"danger"==i?this.elResponseBox.addClass("alert-danger"):"success"==i?this.elResponseBox.addClass("alert-success"):"warning"==i?this.elResponseBox.addClass("alert-warning"):this.elResponseBox.addClass("alert-info"),jQuery("html, body").animate({scrollTop:this.elResponseBox.offset().top-this.elResponseBox.outerHeight(!0)/2},500),!0!==o&&(this.elTimeoutInProgress=!0,setTimeout((function(){l.elTimeoutInProgress&&l.hideAlert(),!0===s&&window.location.reload()}),5e3))},AlertMessageHandler.prototype.hideAlert=function(){this.elResponseBox.addClass("d-none"),this.elResponseBox.removeClass("d-flex"),this.elResponseBox.removeClass("alert-info"),this.elResponseBox.removeClass("alert-danger"),this.elResponseBox.removeClass("alert-success"),this.elResponseBox.removeClass("alert-warning"),this.elResponseTxt.empty(),this.elTimeoutInProgress=!1},AlertMessageHandler.prototype.init=function(e){if(this.elResponseBox=jQuery(e),1!=this.elResponseBox.length)throw new Error("[AlertMessageHandler] - Alert box id invalid");if(this.elResponseTxt=this.elResponseBox.find("> .alert-text"),1!=this.elResponseTxt.length)throw new Error("[AlertMessageHandler] - Alert text box invalid");if(this.elDismissBtn=this.elResponseBox.find("> button.alert-dismiss-btn"),1!=this.elDismissBtn.length)throw new Error("[AlertMessageHandler] - Alert dismiss button not found");this.elDismissBtn.click(this.hideAlert.bind(this))},e.exports=AlertMessageHandler}},t={};function __webpack_require__(s){var o=t[s];if(void 0!==o)return o.exports;var l=t[s]={exports:{}};return e[s](l,l.exports,__webpack_require__),l.exports}(()=>{var e=__webpack_require__(1838);function TemplateUploadClient(){this.elDocUploadName=jQuery("#requisigner-document-name"),this.elDocUploadInput=jQuery("#requisigner-document-upload"),this.elDocUploadForm=jQuery("#requisigner-document-upload-form"),this.elDocUploadInput.filepond(),this.elDocUploadInput.filepond("storeAsFile",!0),this.elDocUploadInput.filepond("allowMultiple",!1),this.elDocUploadInput.filepond("acceptedFileTypes",["application/pdf"]),this.elDocUploadInput.filepond("maxFileSize",REQUISIGNER_UPLOAD_MAX_SIZE),this.oQuillOps={modules:{toolbar:[["bold","italic","underline","strike"],[{script:"sub"},{script:"super"}],[{align:[]}],[{size:["small",!1,"large","huge"]}],[{header:[1,2,3,4,5,6,!1]}],["link","image","blockquote"],[{list:"ordered"},{list:"bullet"}],["clean"]]},placeholder:"Enter description here (optional)...",theme:"snow"},this.qjsDocumentDescr=new Quill("#requisigner-document-description",this.oQuillOps),this.oHoldonOpts={theme:"sk-circle"},this.amhAlertHandler=new e,this.amhAlertHandler.init("#requisigner-main-alert-box"),this.elDocUploadForm.submit(this.postDocument.bind(this))}TemplateUploadClient.prototype.postDocument=function(e){var t=this;e.preventDefault();var s=this.elDocUploadName.val().trim(),o=new FormData(this.elDocUploadForm.get(0));o.append("document_description",this.qjsDocumentDescr.root.innerHTML),s.length<3?this.amhAlertHandler.showAlert("danger","Document name must be at least 3 characters!"):0!=o.get("document_file").length?(o.has("group_read")||o.append("group_read",0),o.has("group_edit")||o.append("group_edit",0),o.has("subgroup_read")||o.append("subgroup_read",0),o.has("subgroup_edit")||o.append("subgroup_edit",0),this.elDocUploadName.val(s),this.oHoldonOpts.message="Uploading to library...",HoldOn.open(this.oHoldonOpts),jQuery.ajax({type:"POST",url:this.elDocUploadForm.attr("action"),data:o,processData:!1,contentType:!1}).done((function(e){200==e.code?window.location.href=e.result:Array.isArray(e.result)?t.amhAlertHandler.showAlert("danger",e.result.join("<br>")):t.amhAlertHandler.showAlert("danger",e.result)})).always((function(){HoldOn.close()}))):this.amhAlertHandler.showAlert("danger","You must select a PDF file to upload!")},jQuery(document).ready((function(){FilePond.registerPlugin(FilePondPluginFileValidateSize),FilePond.registerPlugin(FilePondPluginFileValidateType);new TemplateUploadClient}))})()})();