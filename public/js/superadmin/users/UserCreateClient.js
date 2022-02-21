(()=>{var e={838:e=>{function AlertMessageHandler(){this.elTimeoutInProgress=!1}AlertMessageHandler.prototype.showAlert=function(e,s,t,r){var n=this,o=null==s?"":s.trim(),l=void 0===s||null===e?"danger":e;0==o.length&&(o="A critical system error occurred! Please try again later!"),this.elResponseTxt.html(o),this.elResponseBox.addClass("d-flex"),this.elResponseBox.removeClass("d-none"),"danger"==l?this.elResponseBox.addClass("alert-danger"):"success"==l?this.elResponseBox.addClass("alert-success"):"warning"==l?this.elResponseBox.addClass("alert-warning"):this.elResponseBox.addClass("alert-info"),jQuery("html, body").animate({scrollTop:this.elResponseBox.offset().top-this.elResponseBox.outerHeight(!0)/2},500),!0!==r&&(this.elTimeoutInProgress=!0,setTimeout((function(){n.elTimeoutInProgress&&n.hideAlert(),!0===t&&window.location.reload()}),5e3))},AlertMessageHandler.prototype.hideAlert=function(){this.elResponseBox.addClass("d-none"),this.elResponseBox.removeClass("d-flex"),this.elResponseBox.removeClass("alert-info"),this.elResponseBox.removeClass("alert-danger"),this.elResponseBox.removeClass("alert-success"),this.elResponseBox.removeClass("alert-warning"),this.elResponseTxt.empty(),this.elTimeoutInProgress=!1},AlertMessageHandler.prototype.init=function(e){if(this.elResponseBox=jQuery(e),1!=this.elResponseBox.length)throw new Error("[AlertMessageHandler] - Alert box id invalid");if(this.elResponseTxt=this.elResponseBox.find("> .alert-text"),1!=this.elResponseTxt.length)throw new Error("[AlertMessageHandler] - Alert text box invalid");if(this.elDismissBtn=this.elResponseBox.find("> button.alert-dismiss-btn"),1!=this.elDismissBtn.length)throw new Error("[AlertMessageHandler] - Alert dismiss button not found");this.elDismissBtn.click(this.hideAlert.bind(this))},e.exports=AlertMessageHandler}},s={};function __webpack_require__(t){var r=s[t];if(void 0!==r)return r.exports;var n=s[t]={exports:{}};return e[t](n,n.exports,__webpack_require__),n.exports}(()=>{var e=__webpack_require__(838);function UserCreateClient(){this.oHoldonOpts={theme:"sk-circle"},this.amhAlertHandler=new e,this.amhAlertHandler.init("#requisigner-main-alert-box"),this.elUserCreateForm=jQuery("#requisigner-create-user-form"),this.elUserCreateForm.submit(this.postUser.bind(this))}UserCreateClient.prototype.postUser=function(e){var s=this;e.preventDefault();var t=new FormData(this.elUserCreateForm.get(0));if(t.get("username").length<3)this.amhAlertHandler.showAlert("danger","Username must be at least 3 characters!");else if(t.get("password").length<6)this.amhAlertHandler.showAlert("danger","Password must be at least 6 characters!");else{var r=t.get("human_name");0!=(r=r.trim()).length?(t.set("human_name",r),this.oHoldonOpts.message="Creating user...",HoldOn.open(this.oHoldonOpts),jQuery.ajax({type:"POST",url:this.elUserCreateForm.attr("action"),data:t,processData:!1,contentType:!1}).done((function(e){200==e.code?(s.amhAlertHandler.showAlert("success",e.result),s.elUserCreateForm.get(0).reset()):Array.isArray(e.result)?s.amhAlertHandler.showAlert("danger",e.result.join("<br>")):s.amhAlertHandler.showAlert("danger",e.result)})).always((function(){HoldOn.close()}))):this.amhAlertHandler.showAlert("danger","You must enter a human name for this user!")}},jQuery(document).ready((function(){new UserCreateClient}))})()})();