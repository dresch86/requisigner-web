(()=>{var e={1838:e=>{function AlertMessageHandler(){this.elTimeoutInProgress=!1}AlertMessageHandler.prototype.showAlert=function(e,s,t,r){var o=this,i=null==s?"":s.trim(),l=void 0===s||null===e?"danger":e;0==i.length&&(i="A critical system error occurred! Please try again later!"),this.elResponseTxt.html(i),this.elResponseBox.addClass("d-flex"),this.elResponseBox.removeClass("d-none"),"danger"==l?this.elResponseBox.addClass("alert-danger"):"success"==l?this.elResponseBox.addClass("alert-success"):"warning"==l?this.elResponseBox.addClass("alert-warning"):this.elResponseBox.addClass("alert-info"),jQuery("html, body").animate({scrollTop:this.elResponseBox.offset().top-this.elResponseBox.outerHeight(!0)/2},500),!0!==r&&(this.elTimeoutInProgress=!0,setTimeout((function(){o.elTimeoutInProgress&&o.hideAlert(),!0===t&&window.location.reload()}),5e3))},AlertMessageHandler.prototype.hideAlert=function(){this.elResponseBox.addClass("d-none"),this.elResponseBox.removeClass("d-flex"),this.elResponseBox.removeClass("alert-info"),this.elResponseBox.removeClass("alert-danger"),this.elResponseBox.removeClass("alert-success"),this.elResponseBox.removeClass("alert-warning"),this.elResponseTxt.empty(),this.elTimeoutInProgress=!1},AlertMessageHandler.prototype.init=function(e){if(this.elResponseBox=jQuery(e),1!=this.elResponseBox.length)throw new Error("[AlertMessageHandler] - Alert box id invalid");if(this.elResponseTxt=this.elResponseBox.find("> .alert-text"),1!=this.elResponseTxt.length)throw new Error("[AlertMessageHandler] - Alert text box invalid");if(this.elDismissBtn=this.elResponseBox.find("> button.alert-dismiss-btn"),1!=this.elDismissBtn.length)throw new Error("[AlertMessageHandler] - Alert dismiss button not found");this.elDismissBtn.click(this.hideAlert.bind(this))},e.exports=AlertMessageHandler}},s={};function __webpack_require__(t){var r=s[t];if(void 0!==r)return r.exports;var o=s[t]={exports:{}};return e[t](o,o.exports,__webpack_require__),o.exports}(()=>{var e=__webpack_require__(1838);function ProfileClient(){this.amhAlertHandler=new e,this.amhAlertHandler.init("#requisigner-main-alert-box"),this.elProfileForm=jQuery("#requisigner-user-profile"),this.elProfileForm.submit(this.saveProfile.bind(this)),this.reRFCEmail=/^((([!#$%&'*+\-/=?^_`{|}~\w])|([!#$%&'*+\-/=?^_`{|}~\w][!#$%&'*+\-/=?^_`{|}~\.\w]{0,}[!#$%&'*+\-/=?^_`{|}~\w]))[@]\w+([-.]\w+)*\.\w+([-.]\w+)*)$/,this.oHoldonOpts={theme:"sk-circle"}}ProfileClient.prototype.saveProfile=function(e){var s=this;e.preventDefault();var t=[],r=new FormData(this.elProfileForm.get(0)),o=r.get("email").trim(),i=r.get("human_name").trim(),l=r.get("password").trim(),n=r.get("password_confirm").trim();0==i.length&&t.push("Your name is a required field!"),this.reRFCEmail.test(o)||t.push("Your email address contains invalid characters!"),l.length>0&&l!=n&&t.push("Passwords do not match!"),this.oHoldonOpts.message="Saving Profile...",HoldOn.open(this.oHoldonOpts),jQuery.ajax({type:"POST",url:this.elProfileForm.attr("action"),data:r,processData:!1,contentType:!1}).done((function(e){200==e.code?s.amhAlertHandler.showAlert("success",e.result):Array.isArray(e.result)?s.amhAlertHandler.showAlert("danger",e.result.join("<br>")):s.amhAlertHandler.showAlert("danger",e.result)})).always((function(){HoldOn.close()}))},jQuery(document).ready((function(){new ProfileClient}))})()})();