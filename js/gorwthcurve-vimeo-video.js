!function(e){var t={};function n(i){if(t[i])return t[i].exports;var r=t[i]={i:i,l:!1,exports:{}};return e[i].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=e,n.c=t,n.d=function(e,t,i){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:i})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var i=Object.create(null);if(n.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(i,r,function(t){return e[t]}.bind(null,r));return i},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="/",n(n.s=4)}({4:function(e,t,n){e.exports=n(5)},5:function(e,t){function n(e,t){for(var n=0;n<t.length;n++){var i=t[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,i.key,i)}}var i=function(){function e(t,n){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e);var i=this;i.$element=t,i.$iframe=i.$element.find("iframe"),i.src=i.$iframe.data("src"),i.alt=i.$iframe.data("alt")||"Accept Third Party",i.buttonTemplate='<button class="btn btn-info center-block display-cookies-disclaimer-popup">'+i.alt+"</button>",i.isAccepted()?(i.showVideo(),i.active=!0):(i.blockVideo(),i.active=!1),window.ACC.registerThirdParty(this)}var t,i,r;return t=e,(i=[{key:"isEnabled",value:function(){return this.active}},{key:"enable",value:function(){this.active||(this.showVideo(),this.active=!0)}},{key:"disable",value:function(){this.active&&(this.blockVideo(),this.active=!1)}},{key:"isAccepted",value:function(){return!window.ACC.UserPrivacy.isOptedOut("thirdParty")}},{key:"showVideo",value:function(){this.$iframe.prop("src",this.src),this.$element.find("button.display-cookies-disclaimer-popup").remove(),this.$element.find(".vvResponsive").fitVids()}},{key:"blockVideo",value:function(){this.$element.append(this.buttonTemplate)}}])&&n(t.prototype,i),r&&n(t,r),e}();$.fn.xwACCVimeo=function(e){return $.each($(this),(function(t,n){new i($(this),e)}))},$((function(){var e=$("div.vimeoVidWrap");e.length>0&&e.xwACCVimeo()}))}});