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
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets/js/google-map.js":
/*!*********************************!*\
  !*** ./assets/js/google-map.js ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var GoogleMap = /*#__PURE__*/function () {
  function GoogleMap(element) {
    _classCallCheck(this, GoogleMap);

    var my = this;
    my.element = element;
    my.$element = $(element);
    my.latitude = my.$element.data('latitude');
    my.longitude = my.$element.data('longitude');
    my.zoom = my.$element.data('zoom');
    my.scrollwheel = my.$element.data('scrollwheel');
    my.draggable = my.$element.data('draggable');
    my.latlng = new window.google.maps.LatLng(my.latitude, my.longitude);
    my.width = my.$element.data('width');
    my.height = my.$element.data('height');
    my.alt = my.$element.data('alt') || '';
    my.buttonText = my.$element.data('buttonText') || 'Accept Third Party';
    my.popupMessage = my.$element.data('popupMessage');
    my.buttonTemplate = '<button class="btn btn-info center-block display-cookies-disclaimer-popup">' + my.buttonText + '</button>';
    my.mapOptions = {
      zoom: my.zoom,
      center: my.latlng,
      mapTypeId: window.google.maps.MapTypeId.ROADMAP,
      streetViewControl: false,
      scrollwheel: my.scrollwheel,
      draggable: my.draggable,
      mapTypeControl: false
    };
    my.active = my.$element.data('active') || false;
    my.blockGoogleMapTemplate = '<div class="block-google-map-template" style="width: ' + my.width + ';height: ' + my.height + ';"><div class="block-googlemap-overlay"><div class="popup-message">' + my.popupMessage + my.buttonTemplate + '</div></div></div>';

    if (!my.active) {
      my.blockGoogleMap();
    } else {
      my.showGoogleMap();
    }

    window.ACC.registerThirdParty(this);
  }
  /**
   * Method Required by {ThirdPartyManager} class under ACC
   *
   * @returns {boolean}
   */


  _createClass(GoogleMap, [{
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
        this.showGoogleMap();
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
        this.blockGoogleMap();
        this.active = false;
      }
    }
  }, {
    key: "isAccepted",
    value: function isAccepted() {
      return !window.ACC.UserPrivacy.isOptedOut('thirdParty');
    }
  }, {
    key: "showGoogleMap",
    value: function showGoogleMap() {
      var my = this;

      try {
        var map = new window.google.maps.Map(my.element, my.mapOptions);
        new window.google.maps.Marker({
          position: my.latlng,
          map: map
        });
      } catch (e) {
        my.$element.replaceWith($('<p />').text(e.message));
      }
    }
  }, {
    key: "blockGoogleMap",
    value: function blockGoogleMap() {
      var my = this;
      my.$element.append(my.blockGoogleMapTemplate);
    }
  }]);

  return GoogleMap;
}();

window.concreteGoogleMapInit = function () {
  $('.googleMapCanvas').each(function () {
    new GoogleMap(this);
  });
};

/***/ }),

/***/ "./assets/sass/google-map.scss":
/*!*************************************!*\
  !*** ./assets/sass/google-map.scss ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./assets/sass/youtube.scss":
/*!**********************************!*\
  !*** ./assets/sass/youtube.scss ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!************************************************************************************************!*\
  !*** multi ./assets/js/google-map.js ./assets/sass/google-map.scss ./assets/sass/youtube.scss ***!
  \************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! C:\wamp64\www\pneumo\packages\active_cookie_consent_third_party\build\assets\js\google-map.js */"./assets/js/google-map.js");
__webpack_require__(/*! C:\wamp64\www\pneumo\packages\active_cookie_consent_third_party\build\assets\sass\google-map.scss */"./assets/sass/google-map.scss");
module.exports = __webpack_require__(/*! C:\wamp64\www\pneumo\packages\active_cookie_consent_third_party\build\assets\sass\youtube.scss */"./assets/sass/youtube.scss");


/***/ })

/******/ });