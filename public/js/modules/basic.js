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

/***/ "./resources/js/modules/basic.js":
/*!***************************************!*\
  !*** ./resources/js/modules/basic.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var r_menu_buttons_show_hide_frame_speed = 40;
var r_menu_buttons_show_hide_frames_number = 16;
var r_menu_buttons_show_hide_frame_width = 34;

function r_menu_buttons_show() {
  var i = 0;
  var interval = setInterval(function () {
    $('.navbar__toggler-icon').css('background-position', '-' + i * r_menu_buttons_show_hide_frame_width + 'px 0px');
    i++;

    if (i === r_menu_buttons_show_hide_frames_number) {
      clearInterval(interval);
    }
  }, r_menu_buttons_show_hide_frame_speed);
}

function r_menu_buttons_hide() {
  var i = r_menu_buttons_show_hide_frames_number;
  var interval = setInterval(function () {
    i--;
    $('.navbar__toggler-icon').css('background-position', '-' + i * r_menu_buttons_show_hide_frame_width + 'px 0px');

    if (i === 0) {
      clearInterval(interval);
    }
  }, r_menu_buttons_show_hide_frame_speed);
}

function search_expansion() {
  $('.js_open_search').on('click', function () {
    $(this).hide();
    $('.js_close_search').show();
    $('.js_search').addClass('expanded');
  });
  $('.js_close_search').on('click', function () {
    $(this).hide();
    $('.js_open_search').show();
    $('.js_search').removeClass('expanded');
  });
}

$(document).ready(function () {
  search_expansion();
  $('nav').on('hide.bs.collapse', function () {
    r_menu_buttons_hide();
  });
  $('nav').on('show.bs.collapse', function () {
    r_menu_buttons_show();
  });
});

/***/ }),

/***/ 1:
/*!*********************************************!*\
  !*** multi ./resources/js/modules/basic.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\MAMP\htdocs\laravel-template\resources\js\modules\basic.js */"./resources/js/modules/basic.js");


/***/ })

/******/ });