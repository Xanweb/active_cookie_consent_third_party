/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/js/youtube.js":
/*!******************************!*\
  !*** ./assets/js/youtube.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Youtube = /*#__PURE__*/function () {
  function Youtube($element, options) {
    _classCallCheck(this, Youtube);

    var my = this;
    my.$element = $element;
    my.$iframe = my.$element.find('iframe');
    my.src = my.$iframe.data('src');
    my.alt = my.$iframe.data('alt') || 'Accept Third Party';
    my.active = my.$iframe.data('activate') || 0;
    my.popupMessage = my.$iframe.data('popupMessage');
    my.buttonTemplate = '<button class="btn btn-info center-block display-cookies-disclaimer-popup">' + my.alt + '</button>';
    my.blockYoutubeTemplate = '<div class="block-youtube-overlay"><div class="popup-message">' + my.popupMessage + my.buttonTemplate + '</div></div>';

    if (!my.active) {
      my.blockVideo();
    } else {
      my.showVideo();
    }

    window.ACC.registerThirdParty(this);
  }
  /**
   * Method Required by {ThirdPartyManager} class under ACC
   *
   * @returns {boolean}
   */


  _createClass(Youtube, [{
    key: "isEnabled",
    value: function isEnabled() {
      return this.active;
    }
    /**
     * Method Required by {ThirdPartyManager} class under ACC
     */

  }, {
    key: "enable",
    value: function enable() {
      if (!this.active) {
        this.showVideo();
        this.active = true;
      }
    }
    /**
     * Method Required by {ThirdPartyManager} class under ACC
     */

  }, {
    key: "disable",
    value: function disable() {
      if (this.active) {
        this.blockVideo();
        this.active = false;
      }
    }
  }, {
    key: "isAccepted",
    value: function isAccepted() {
      return !window.ACC.UserPrivacy.isOptedOut('thirdParty');
    }
  }, {
    key: "showVideo",
    value: function showVideo() {
      this.$iframe.prop('src', this.src);
      this.$element.find('.blockYoutubeTemplate').remove();
    }
  }, {
    key: "blockVideo",
    value: function blockVideo() {
      this.$element.append(this.blockYoutubeTemplate);
    }
  }]);

  return Youtube;
}();

$.fn.xwACCYoutube = function (options) {
  return $.each($(this), function (i, obj) {
    new Youtube($(this), options);
  });
}; // Setup AutoStart


$(function () {
  var youtubePlayer = $('div.youtubeBlock');

  if (youtubePlayer.length > 0) {
    youtubePlayer.xwACCYoutube();
  }
});

/***/ }),

/***/ 1:
/*!************************************!*\
  !*** multi ./assets/js/youtube.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\wamp64\www\pneumo\packages\active_cookie_consent_third_party\build\assets\js\youtube.js */"./assets/js/youtube.js");


/***/ })

/******/ });