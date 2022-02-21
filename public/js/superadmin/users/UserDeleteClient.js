(()=>{var e={838:e=>{function AlertMessageHandler(){this.elTimeoutInProgress=!1}AlertMessageHandler.prototype.showAlert=function(e,s,t,o){var r=this,l=null==s?"":s.trim(),n=void 0===s||null===e?"danger":e;0==l.length&&(l="A critical system error occurred! Please try again later!"),this.elResponseTxt.html(l),this.elResponseBox.addClass("d-flex"),this.elResponseBox.removeClass("d-none"),"danger"==n?this.elResponseBox.addClass("alert-danger"):"success"==n?this.elResponseBox.addClass("alert-success"):"warning"==n?this.elResponseBox.addClass("alert-warning"):this.elResponseBox.addClass("alert-info"),jQuery("html, body").animate({scrollTop:this.elResponseBox.offset().top-this.elResponseBox.outerHeight(!0)/2},500),!0!==o&&(this.elTimeoutInProgress=!0,setTimeout((function(){r.elTimeoutInProgress&&r.hideAlert(),!0===t&&window.location.reload()}),5e3))},AlertMessageHandler.prototype.hideAlert=function(){this.elResponseBox.addClass("d-none"),this.elResponseBox.removeClass("d-flex"),this.elResponseBox.removeClass("alert-info"),this.elResponseBox.removeClass("alert-danger"),this.elResponseBox.removeClass("alert-success"),this.elResponseBox.removeClass("alert-warning"),this.elResponseTxt.empty(),this.elTimeoutInProgress=!1},AlertMessageHandler.prototype.init=function(e){if(this.elResponseBox=jQuery(e),1!=this.elResponseBox.length)throw new Error("[AlertMessageHandler] - Alert box id invalid");if(this.elResponseTxt=this.elResponseBox.find("> .alert-text"),1!=this.elResponseTxt.length)throw new Error("[AlertMessageHandler] - Alert text box invalid");if(this.elDismissBtn=this.elResponseBox.find("> button.alert-dismiss-btn"),1!=this.elDismissBtn.length)throw new Error("[AlertMessageHandler] - Alert dismiss button not found");this.elDismissBtn.click(this.hideAlert.bind(this))},e.exports=AlertMessageHandler}},s={};function __webpack_require__(t){var o=s[t];if(void 0!==o)return o.exports;var r=s[t]={exports:{}};return e[t](r,r.exports,__webpack_require__),r.exports}(()=>{var e=__webpack_require__(838);function UserDeleteClient(){this.oHoldonOpts={theme:"sk-circle"},this.amhAlertHandler=new e,this.amhAlertHandler.init("#requisigner-main-alert-box"),this.elUserRecordTableBody=jQuery("#requisigner-user-list > tbody"),this.elUserRecordTableBody.click(this.handleControl.bind(this)),this.elBSModal=jQuery("#requisigner-delete-confirm"),this.bsDeleteConfirm=new bootstrap.Modal(this.elBSModal.get(0)),this.elDeleteConfirmControls=this.elBSModal.find(".modal-footer > button"),this.sCSRFToken=jQuery('meta[name="csrf_token"]').attr("content")}UserDeleteClient.prototype.deleteUser=function(e){var s=this,t=!1;this.elDeleteConfirmControls.click((function(e){"Yes"==jQuery(e.target).text()&&(t=!0),s.elDeleteConfirmControls.unbind(),s.bsDeleteConfirm.hide()})),this.elBSModal.on("hidden.bs.modal",(function(o){t&&(s.oHoldonOpts.message="Deleting user...",HoldOn.open(s.oHoldonOpts),jQuery.ajax({type:"POST",url:REQUISIGNER_DELETE_USER_URL,data:JSON.stringify({user_id:e}),contentType:"application/json",processData:!1,headers:{"X-CSRF-TOKEN":s.sCSRFToken}}).done((function(e){200==e.code?s.amhAlertHandler.showAlert("success",e.result,!0):s.amhAlertHandler.showAlert("danger",e.result)})).always((function(){HoldOn.close()}))),s.elBSModal.unbind()})),this.bsDeleteConfirm.show()},UserDeleteClient.prototype.handleControl=function(e){var s=jQuery(e.target);if(null!=s.data("control")){if("delete"!==s.data("control"))return;this.deleteUser(s.data("userId"))}},jQuery(document).ready((function(){new UserDeleteClient}))})()})();