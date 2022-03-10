(()=>{var e={7757:(e,t,r)=>{e.exports=r(5666)},1838:e=>{function AlertMessageHandler(){this.elTimeoutInProgress=!1}AlertMessageHandler.prototype.showAlert=function(e,t,r,n){var o=this,i=null==t?"":t.trim(),a=void 0===t||null===e?"danger":e;0==i.length&&(i="A critical system error occurred! Please try again later!"),this.elResponseTxt.html(i),this.elResponseBox.addClass("d-flex"),this.elResponseBox.removeClass("d-none"),"danger"==a?this.elResponseBox.addClass("alert-danger"):"success"==a?this.elResponseBox.addClass("alert-success"):"warning"==a?this.elResponseBox.addClass("alert-warning"):this.elResponseBox.addClass("alert-info"),jQuery("html, body").animate({scrollTop:this.elResponseBox.offset().top-this.elResponseBox.outerHeight(!0)/2},500),!0!==n&&(this.elTimeoutInProgress=!0,setTimeout((function(){o.elTimeoutInProgress&&o.hideAlert(),!0===r&&window.location.reload()}),5e3))},AlertMessageHandler.prototype.hideAlert=function(){this.elResponseBox.addClass("d-none"),this.elResponseBox.removeClass("d-flex"),this.elResponseBox.removeClass("alert-info"),this.elResponseBox.removeClass("alert-danger"),this.elResponseBox.removeClass("alert-success"),this.elResponseBox.removeClass("alert-warning"),this.elResponseTxt.empty(),this.elTimeoutInProgress=!1},AlertMessageHandler.prototype.init=function(e){if(this.elResponseBox=jQuery(e),1!=this.elResponseBox.length)throw new Error("[AlertMessageHandler] - Alert box id invalid");if(this.elResponseTxt=this.elResponseBox.find("> .alert-text"),1!=this.elResponseTxt.length)throw new Error("[AlertMessageHandler] - Alert text box invalid");if(this.elDismissBtn=this.elResponseBox.find("> button.alert-dismiss-btn"),1!=this.elDismissBtn.length)throw new Error("[AlertMessageHandler] - Alert dismiss button not found");this.elDismissBtn.click(this.hideAlert.bind(this))},e.exports=AlertMessageHandler},5666:e=>{var t=function(e){"use strict";var t,r=Object.prototype,n=r.hasOwnProperty,o="function"==typeof Symbol?Symbol:{},i=o.iterator||"@@iterator",a=o.asyncIterator||"@@asyncIterator",s=o.toStringTag||"@@toStringTag";function define(e,t,r){return Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{define({},"")}catch(e){define=function(e,t,r){return e[t]=r}}function wrap(e,t,r,n){var o=t&&t.prototype instanceof Generator?t:Generator,i=Object.create(o.prototype),a=new Context(n||[]);return i._invoke=function makeInvokeMethod(e,t,r){var n=l;return function invoke(o,i){if(n===u)throw new Error("Generator is already running");if(n===h){if("throw"===o)throw i;return doneResult()}for(r.method=o,r.arg=i;;){var a=r.delegate;if(a){var s=maybeInvokeDelegate(a,r);if(s){if(s===d)continue;return s}}if("next"===r.method)r.sent=r._sent=r.arg;else if("throw"===r.method){if(n===l)throw n=h,r.arg;r.dispatchException(r.arg)}else"return"===r.method&&r.abrupt("return",r.arg);n=u;var f=tryCatch(e,t,r);if("normal"===f.type){if(n=r.done?h:c,f.arg===d)continue;return{value:f.arg,done:r.done}}"throw"===f.type&&(n=h,r.method="throw",r.arg=f.arg)}}}(e,r,a),i}function tryCatch(e,t,r){try{return{type:"normal",arg:e.call(t,r)}}catch(e){return{type:"throw",arg:e}}}e.wrap=wrap;var l="suspendedStart",c="suspendedYield",u="executing",h="completed",d={};function Generator(){}function GeneratorFunction(){}function GeneratorFunctionPrototype(){}var f={};define(f,i,(function(){return this}));var p=Object.getPrototypeOf,y=p&&p(p(values([])));y&&y!==r&&n.call(y,i)&&(f=y);var g=GeneratorFunctionPrototype.prototype=Generator.prototype=Object.create(f);function defineIteratorMethods(e){["next","throw","return"].forEach((function(t){define(e,t,(function(e){return this._invoke(t,e)}))}))}function AsyncIterator(e,t){function invoke(r,o,i,a){var s=tryCatch(e[r],e,o);if("throw"!==s.type){var l=s.arg,c=l.value;return c&&"object"==typeof c&&n.call(c,"__await")?t.resolve(c.__await).then((function(e){invoke("next",e,i,a)}),(function(e){invoke("throw",e,i,a)})):t.resolve(c).then((function(e){l.value=e,i(l)}),(function(e){return invoke("throw",e,i,a)}))}a(s.arg)}var r;this._invoke=function enqueue(e,n){function callInvokeWithMethodAndArg(){return new t((function(t,r){invoke(e,n,t,r)}))}return r=r?r.then(callInvokeWithMethodAndArg,callInvokeWithMethodAndArg):callInvokeWithMethodAndArg()}}function maybeInvokeDelegate(e,r){var n=e.iterator[r.method];if(n===t){if(r.delegate=null,"throw"===r.method){if(e.iterator.return&&(r.method="return",r.arg=t,maybeInvokeDelegate(e,r),"throw"===r.method))return d;r.method="throw",r.arg=new TypeError("The iterator does not provide a 'throw' method")}return d}var o=tryCatch(n,e.iterator,r.arg);if("throw"===o.type)return r.method="throw",r.arg=o.arg,r.delegate=null,d;var i=o.arg;return i?i.done?(r[e.resultName]=i.value,r.next=e.nextLoc,"return"!==r.method&&(r.method="next",r.arg=t),r.delegate=null,d):i:(r.method="throw",r.arg=new TypeError("iterator result is not an object"),r.delegate=null,d)}function pushTryEntry(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function resetTryEntry(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function Context(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(pushTryEntry,this),this.reset(!0)}function values(e){if(e){var r=e[i];if(r)return r.call(e);if("function"==typeof e.next)return e;if(!isNaN(e.length)){var o=-1,a=function next(){for(;++o<e.length;)if(n.call(e,o))return next.value=e[o],next.done=!1,next;return next.value=t,next.done=!0,next};return a.next=a}}return{next:doneResult}}function doneResult(){return{value:t,done:!0}}return GeneratorFunction.prototype=GeneratorFunctionPrototype,define(g,"constructor",GeneratorFunctionPrototype),define(GeneratorFunctionPrototype,"constructor",GeneratorFunction),GeneratorFunction.displayName=define(GeneratorFunctionPrototype,s,"GeneratorFunction"),e.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===GeneratorFunction||"GeneratorFunction"===(t.displayName||t.name))},e.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,GeneratorFunctionPrototype):(e.__proto__=GeneratorFunctionPrototype,define(e,s,"GeneratorFunction")),e.prototype=Object.create(g),e},e.awrap=function(e){return{__await:e}},defineIteratorMethods(AsyncIterator.prototype),define(AsyncIterator.prototype,a,(function(){return this})),e.AsyncIterator=AsyncIterator,e.async=function(t,r,n,o,i){void 0===i&&(i=Promise);var a=new AsyncIterator(wrap(t,r,n,o),i);return e.isGeneratorFunction(r)?a:a.next().then((function(e){return e.done?e.value:a.next()}))},defineIteratorMethods(g),define(g,s,"Generator"),define(g,i,(function(){return this})),define(g,"toString",(function(){return"[object Generator]"})),e.keys=function(e){var t=[];for(var r in e)t.push(r);return t.reverse(),function next(){for(;t.length;){var r=t.pop();if(r in e)return next.value=r,next.done=!1,next}return next.done=!0,next}},e.values=values,Context.prototype={constructor:Context,reset:function(e){if(this.prev=0,this.next=0,this.sent=this._sent=t,this.done=!1,this.delegate=null,this.method="next",this.arg=t,this.tryEntries.forEach(resetTryEntry),!e)for(var r in this)"t"===r.charAt(0)&&n.call(this,r)&&!isNaN(+r.slice(1))&&(this[r]=t)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(e){if(this.done)throw e;var r=this;function handle(n,o){return a.type="throw",a.arg=e,r.next=n,o&&(r.method="next",r.arg=t),!!o}for(var o=this.tryEntries.length-1;o>=0;--o){var i=this.tryEntries[o],a=i.completion;if("root"===i.tryLoc)return handle("end");if(i.tryLoc<=this.prev){var s=n.call(i,"catchLoc"),l=n.call(i,"finallyLoc");if(s&&l){if(this.prev<i.catchLoc)return handle(i.catchLoc,!0);if(this.prev<i.finallyLoc)return handle(i.finallyLoc)}else if(s){if(this.prev<i.catchLoc)return handle(i.catchLoc,!0)}else{if(!l)throw new Error("try statement without catch or finally");if(this.prev<i.finallyLoc)return handle(i.finallyLoc)}}}},abrupt:function(e,t){for(var r=this.tryEntries.length-1;r>=0;--r){var o=this.tryEntries[r];if(o.tryLoc<=this.prev&&n.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var i=o;break}}i&&("break"===e||"continue"===e)&&i.tryLoc<=t&&t<=i.finallyLoc&&(i=null);var a=i?i.completion:{};return a.type=e,a.arg=t,i?(this.method="next",this.next=i.finallyLoc,d):this.complete(a)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),d},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.finallyLoc===e)return this.complete(r.completion,r.afterLoc),resetTryEntry(r),d}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.tryLoc===e){var n=r.completion;if("throw"===n.type){var o=n.arg;resetTryEntry(r)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(e,r,n){return this.delegate={iterator:values(e),resultName:r,nextLoc:n},"next"===this.method&&(this.arg=t),d}},e}(e.exports);try{regeneratorRuntime=t}catch(e){"object"==typeof globalThis?globalThis.regeneratorRuntime=t:Function("r","regeneratorRuntime = r")(t)}}},t={};function __webpack_require__(r){var n=t[r];if(void 0!==n)return n.exports;var o=t[r]={exports:{}};return e[r](o,o.exports,__webpack_require__),o.exports}__webpack_require__.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return __webpack_require__.d(t,{a:t}),t},__webpack_require__.d=(e,t)=>{for(var r in t)__webpack_require__.o(t,r)&&!__webpack_require__.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},__webpack_require__.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{"use strict";var e=__webpack_require__(7757),t=__webpack_require__.n(e);function asyncGeneratorStep(e,t,r,n,o,i,a){try{var s=e[i](a),l=s.value}catch(e){return void r(e)}s.done?t(l):Promise.resolve(l).then(n,o)}function _asyncToGenerator(e){return function(){var t=this,r=arguments;return new Promise((function(n,o){var i=e.apply(t,r);function _next(e){asyncGeneratorStep(i,n,o,_next,_throw,"next",e)}function _throw(e){asyncGeneratorStep(i,n,o,_next,_throw,"throw",e)}_next(void 0)}))}}var r=__webpack_require__(1838);function SignatureClient(){var e=this;this.amhAlertHandler=new r,this.amhAlertHandler.init("#requisigner-main-alert-box"),this.elSigPadCanvas=jQuery("#requisigner-signature-pad"),this.elSigPadCanvas.attr("width","340"),this.elSigPadCanvas.attr("height","100"),this.sCSRFToken=jQuery('meta[name="csrf_token"]').attr("content"),this.spVisibleSigPad=new SignaturePad(this.elSigPadCanvas.get(0)),this.elSigClearBtn=jQuery("#requisigner-vsig-clear-btn"),this.elSigClearBtn.click((function(){e.spVisibleSigPad.clear()})),this.elVisSigSaveBtn=jQuery("#requisigner-vsig-save-btn"),this.elVisSigSaveBtn.click(this.saveVisibleSig.bind(this)),this.spSodiumHandler=window.sodium,this.generateKeypair()}SignatureClient.prototype.saveVisibleSig=function(){var e=this;this.spVisibleSigPad.isEmpty()?this.amhAlertHandler.showAlert("danger","No signature drawn!"):(HoldOn.open({theme:"sk-cube-grid",message:"Saving Visible Signature..."}),jQuery.ajax({type:"POST",url:REQUISIGNER_VSIG_HANDLER_URL,data:JSON.stringify({image:this.spVisibleSigPad.toDataURL(),controlPoints:this.spVisibleSigPad.toData()}),contentType:"application/json",processData:!1,headers:{"X-CSRF-TOKEN":this.sCSRFToken}}).done((function(t){200==t.code?e.amhAlertHandler.showAlert("success",t.result):Array.isArray(t.result)?e.amhAlertHandler.showAlert("danger",t.result.join("<br>")):e.amhAlertHandler.showAlert("danger",t.result)})).always((function(){HoldOn.close()})))},SignatureClient.prototype.generateKeypair=_asyncToGenerator(t().mark((function _callee(){var e,r;return t().wrap((function _callee$(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,this.spSodiumHandler.randombytes_buf(32);case 2:return e=t.sent,t.next=5,this.spSodiumHandler.crypto_generichash("hello world");case 5:r=t.sent,console.log({random:e.toString("hex"),hash:r.toString("hex")});case 7:case"end":return t.stop()}}),_callee,this)}))),SignatureClient.prototype.initSigPad=function(){var e=this;jQuery.ajax({type:"GET",url:REQUISIGNER_VSIG_CPTS_URL}).done((function(t){200==t.code?t.result.length>0&&Array.isArray(t.result)&&e.spVisibleSigPad.fromData(t.result):e.amhAlertHandler.showAlert("danger","Failed to load existing visible signature!")}))},jQuery(document).ready((function(){_asyncToGenerator(t().mark((function _callee2(){return t().wrap((function _callee2$(e){for(;;)switch(e.prev=e.next){case 0:if(window.sodium){e.next=4;break}return e.next=3,SodiumPlus.auto();case 3:window.sodium=e.sent;case 4:(new SignatureClient).initSigPad();case 6:case"end":return e.stop()}}),_callee2)})))()}))})()})();