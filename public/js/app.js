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

/***/ "./node_modules/webpack/buildin/global.js":
/*!***********************************!*\
  !*** (webpack)/buildin/global.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || new Function("return this")();
} catch (e) {
	// This works if the window reference is available
	if (typeof window === "object") g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// require('./bootstrap');
__webpack_require__(/*! ./legacy/components/main */ "./resources/js/legacy/components/main.js");

__webpack_require__(/*! ./legacy/components/clipboard */ "./resources/js/legacy/components/clipboard.js");

__webpack_require__(/*! ./legacy/components/checklist */ "./resources/js/legacy/components/checklist.js");

__webpack_require__(/*! ./legacy/components/droplist */ "./resources/js/legacy/components/droplist.js");

__webpack_require__(/*! ./legacy/components/newsfeedlist */ "./resources/js/legacy/components/newsfeedlist.js");

__webpack_require__(/*! ./legacy/components/screensaver */ "./resources/js/legacy/components/screensaver.js");

__webpack_require__(/*! ./legacy/components/searchbox */ "./resources/js/legacy/components/searchbox.js");

__webpack_require__(/*! ./legacy/components/settings */ "./resources/js/legacy/components/settings.js");

__webpack_require__(/*! ./legacy/components/welcome */ "./resources/js/legacy/components/welcome.js");

__webpack_require__(/*! ./legacy/components/register */ "./resources/js/legacy/components/register.js");

__webpack_require__(/*! ./legacy/components/tabs */ "./resources/js/legacy/components/tabs.js");

window.showDialog = function (title, description) {
  $('.dialog .title').html(title);
  $('.dialog .description').html(description);
  $('.dialog').modal({
    fadeDuration: 100
  });
};

/***/ }),

/***/ "./resources/js/legacy/components/checklist.js":
/*!*****************************************************!*\
  !*** ./resources/js/legacy/components/checklist.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  $('.checklist--form input[type="button"]').on('click', function () {
    if ($('.checklist--form input[type="text"]').val()) {
      postToChecklist({
        item: $('.checklist--form input[type="text"]').val()
      });
    }
  });
  $('.checklist--form input[type="text"]').keypress(function (e) {
    if (e.which === 13) {
      $('.checklist--form input[type="button"]').click();
    }
  });
  $('.js-checklist-item').on('click', function () {
    checkItem(this);
  });
});

function checkItem(el) {
  postToChecklist({
    id: $(el).data('database-id'),
    checked: $(el).is(':checked')
  });
}

function postToChecklist(data) {
  $.post("/checklist/add/", data).then(function (data) {
    $('.checklist--list').html(data);
    $('.checklist--form input[type="text"]').val('');
    $('.js-checklist-item').on('click', function () {
      checkItem(this);
    });
  })["catch"](function () {
    showDialog('Error', 'Updating checklist has failed! Please try again in a moment.');
    return false;
  });
}

/***/ }),

/***/ "./resources/js/legacy/components/clipboard.js":
/*!*****************************************************!*\
  !*** ./resources/js/legacy/components/clipboard.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/** global: textToCopy */
var textToCopy = '';
$(function () {
  $(document).on('click', '.js-copy-to-clipboard, .dropCopy', function () {
    /** global: textToCopy */
    textToCopy = $(this).attr('data-clipboard-text');
    document.execCommand('copy');
  });
});
document.addEventListener('copy', function (e) {
  /** global: textToCopy */
  e.clipboardData.setData('text/plain', textToCopy);
  e.preventDefault();
});

/***/ }),

/***/ "./resources/js/legacy/components/droplist.js":
/*!****************************************************!*\
  !*** ./resources/js/legacy/components/droplist.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var dropDomain = 'https://pvd.onl/';
$('.js-toggle').on('click', function (e) {
  e.preventDefault();
  var targetElement = $($(this).data('toggle-element'));
  targetElement.load(targetElement.data('target'), function () {
    showDropList();
    activateDropListEvents();
  });
});
$('.js-more-droplist').on('click', function () {
  window.location.href = '/droplist/all';
});
$('.content-overlay, .feed-list').on('click', function () {
  hideDropList();
  $('.profileMenu').slideUp();
});
$(function () {
  activateDropListEvents();
});

function showDropList() {
  $('.js-screenshot-list').slideDown();
  $('.content-overlay').fadeIn();
}

function hideDropList() {
  $('.js-screenshot-list').slideUp();
  $('.content-overlay').fadeOut();
}

function activateDropListEvents() {
  $('.dropOpen').on('click', function () {
    var image = $(this).data('image');
    var tab = window.open(dropDomain + image, '_blank');
    tab.focus();
    hideDropList();
  });
  $('.dropCopy').on('click', function () {
    $(this).css('color', 'red');
  });
  $('.dropHide').on('click', function () {
    var image = $(this).data('image');
    dropAction(this, image, 'hide');
  });
  $('.dropRemove').on('click', function () {
    var image = $(this).data('image');
    dropAction(this, image, 'delete');
  });
}

function dropAction(el, file, action) {
  $.ajax({
    method: "GET",
    url: dropDomain + "pages/" + action + ".php",
    data: {
      file: file
    }
  }).done(function (data) {
    $(el).parents(':eq(2)').html(data).css('background', 'none').css('background-color', 'rgba(107, 193, 103, 0.7)').css('text-align', 'center').css('height', 'auto').css('padding', '20px 0');
  });
}

/***/ }),

/***/ "./resources/js/legacy/components/main.js":
/*!************************************************!*\
  !*** ./resources/js/legacy/components/main.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  $('.js-button-parse-url').on('click', function () {
    getUrlMetaData();
  });
  $('.js-form-feed [name="url"]').on('blur', function () {
    getUrlMetaData();
  });
  $('.widget-note textarea').on('blur', function () {
    saveNote($(this));
  });
  $('.widget-note input').on('blur', function () {
    $(this).parent().find('textarea').trigger('blur');
  });
  setInterval(function () {
    $('.js-update-weahter-icon').load('/weather/icon/');
    $('.js-weather-radar').attr('src', 'https://api.buienradar.nl/image/1.0/RadarMapNL?w=500&h=512&time=' + Math.random());
  }, 5 * 60 * 1000);
  $(document).on('click', '.js-open-note', function () {
    $('.widget-note--notes > div').hide();
    $('.note-data-' + $(this).data('note-id')).show();
  }).on('click', '.js-remove-note', function () {
    removeNote($(this).data('id'));
  }).on('click', '.js-update-weather-icon', function () {
    $('.content-overlay').fadeIn();
    $('.js-show-weather-radar').slideDown();
  }).on('click', '.js-show-calendar', function () {
    $('.content-overlay').fadeIn();
    $('.header--bar-calendar-view').slideDown();
  }).on('click', '.content-overlay, .feed-list', function () {
    $('.content-overlay').fadeOut();
    $('.js-show-weather-radar').slideUp();
    $('.profileMenu').slideUp();
    $('.header--bar-calendar-view').slideUp();
  });
  $('.specialTxt').each(function () {
    var p1 = 'peter';
    var p3 = 'vdam';
    var p2 = '.nl';
    var p4 = 'mail';
    var p5 = '@';
    var p6 = 'to';
    $(this).html('<a href="' + p4 + p6 + ':' + p1 + p5 + p1 + p3 + p2 + '">' + p1 + p5 + p1 + p3 + p2 + '</a>');
  });
  $('.page--homepage').on('click', function () {
    $('.page--homepage .header').animate({
      height: '30vh'
    }, 500);
    $('.mainContent, .widget').fadeIn();
  });
  $('.js-homepage-showpage').on('click', function () {
    $('.view').slideUp().delay(100);
    $('.mainContent nav span').removeClass('active');
    $('.' + $(this).data('page')).slideDown();
    $(this).addClass('active');
    $('.page--homepage .feeds').load('/feeds/overview/');
  });
  $('.js-toggle-fullscreen').on('dblclick', function () {
    var sidebarWidth = $('.container .feed-list').css('width');
    var headerColor = '#337dff';

    if ($('.content').css('left') !== '0px') {
      sidebarWidth = 0;
      headerColor = '#000';
    }

    $('.header--bar-actions').toggle('slow');
    $('.header--bar').css('backgroundColor', headerColor);
    $('.content').animate({
      left: sidebarWidth
    }, 1000);
  });
});

function getUrlMetaData() {
  var Url = $('.js-form-feed [name="url"]').val();

  if (Url.length > 0) {
    $('.js-form-feed [name="title"]').val("Loading info...");
    $('.js-form-feed [name="description"]').val("");
    $.ajax({
      method: "POST",
      url: "/meta/",
      data: {
        url: Url
      }
    }).done(function (response) {
      if (response.status == 'success') {
        $('.js-form-feed [name="title"]').val(response.data.title);
        $('.js-form-feed [name="description"]').val(response.data.description);
      } else {
        $('.js-form-feed [name="title"]').val("");
        $('.js-form-feed [name="description"]').val("");
      }
    });
  }
}

function saveNote($el) {
  var id = $el.attr('data-id');
  var name = $el.parent().find('input').val();
  var position = $el.attr('data-position');
  var note = $el.val();
  $.ajax({
    method: "POST",
    url: "/note/save/",
    data: {
      id: id,
      position: position,
      name: name,
      note: note
    },
    beforeSend: function beforeSend() {
      $el.css('color', '#303030');
    }
  }).done(function (response) {
    $el.css('color', 'black');
    $('.note-selector-' + id).text(name);

    if (id.length === 0) {
      $el.attr('data-id', response.data.id);
    }
  }).fail(function () {
    $el.css('color', 'red');
  });
}

function removeNote(id) {
  if (confirm("Are you sure you want to remove this note?")) {
    $.ajax({
      method: "POST",
      url: "/note/remove/",
      data: {
        id: id
      }
    }).done(function () {
      $('.note-selector-' + id).hide();
      $('.note-data-' + id).hide();
    });
  }
}

/***/ }),

/***/ "./resources/js/legacy/components/newsfeedlist.js":
/*!********************************************************!*\
  !*** ./resources/js/legacy/components/newsfeedlist.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global) {$(function () {
  // Endless scroll
  $('.feed-list').jscroll({
    padding: 150,
    nextSelector: 'a.jscroll-next:last',
    contentSelector: '.feed-list-item',
    callback: function callback() {
      $('.jscroll-added:last-of-type .js-action-feed-list-swipe').each(function () {
        var mc = new Hammer(this);
        var that = $(this);
        mc.on('swiperight', function () {
          $(that).find('.pin').trigger('click');
        });
      });
    }
  });
  $('.header--bar').each(function () {
    var mc = new Hammer(this);
    mc.on('swiperight', function () {
      $('.js-return').trigger('click');
    });
  });
  $(document).on('click', '.js-open-url', function () {
    $('.header--bar, footer').css('backgroundColor', '#337dff');
    openPage($(this).data('url') !== '' ? $(this).data('url') : '/nourl/', $(this).data('share-id'), $(this).data('id'));
  }).on('click', '.js-action-feed-list-click', function () {
    $(this).addClass('animated pulse feed-list-item--state-selected');
    $('.feed-list-item').removeClass('feed-list-item--state-selected');
    $('.header--bar, footer').css('backgroundColor', $(this).css('borderLeftColor'));
    openPage($(this).data('url') !== '' ? $(this).data('url') : '/nourl/', $(this).data('share-id'), $(this).data('id'));
  }).on('click', '.js-return', function (e) {
    e.preventDefault();
    $('.header--bar, footer').css('backgroundColor', '#337dff');
    $('.content iframe').prop('src', '/welcome/');
    $('.content').addClass('hide-if-mobile');
    $('aside').removeClass('hide-if-mobile');
    $('.Homepage').removeClass('pageOpen');
    $('footer .pageView').hide();
    $('footer .defaultView').show();
  }).on('click', '.js-reload-page', function () {
    event.preventDefault();
    $('.content-frame').attr('src', '/welcome/');
    $('.urlbar a').text('').attr('data-clipboard-text', '');
    $('.header--bar, footer').css('backgroundColor', '#337dff');
    $('aside').scrollTop(0);
    requestNewFeedItems();
  }).on('click', '.pin', function (e) {
    e.stopImmediatePropagation();
    var that = this;
    $.post("/feed/pin/" + $(this).data('pin-id'), function () {
      $(that).parent().addClass('animated shake');
      $(that).parent().toggleClass('feed-list-item--state-pinned');
    }, 'json');
  }).on('click', '.pip', function (e) {
    e.stopImmediatePropagation();
    openInPictureInPicture(parseUrl($(this).parent().data('url'), false));
  }).on('click', '.js-close-pip', function () {
    $('.content-pictureInPictureFrame').remove();
    $('.content-close-pip').hide();
    $('.content-maximize-pip').hide();
  }).on('click', '.js-modal-trigger', function () {
    $($(this).data('modal-target')).modal({
      fadeDuration: 100
    });
  }).on('click', '.js-form-feed button', function () {
    $.post('/feed/add-item/', $('.js-form-feed').serialize(), function (data) {
      data.status === 'success' ? $.modal.close() : showDialog('Adding item failed!', 'Failed to add item due to a server error.');
    }, 'json');
  }).on('click', '.js-open-new-window', function () {
    window.open($('.urlbar a').attr('href'));
  }).on('click', '.js-visbility-toggle', function () {
    $($(this).data('target')).toggle();
  }).on('click', '.js-send-to-pip', function () {
    switchToPicutreInPicture();
  }).on('click', '.js-send-from-pip', function () {
    switchFromPictureInPicture();
  }).on('click', '.js-open-profile-menu', function () {
    $('.profileMenu').slideToggle();
    $('.content-overlay').fadeIn();
  }).on('click', '.profileMenu', function () {
    $('.profileMenu').slideUp();
    $('.content-overlay').fadeOut();
  }).on('click', '.js-show-hidden-pinned-items', function () {
    $('.hidden-feed-items').hide();
    $('.hidden-pinned-item').fadeIn();
  }).on('click', '.js-refresh-feed-items', function () {
    $('aside').scrollTop(0);
    requestNewFeedItems();
  });
  $('.js-action-feed-list-swipe').each(function () {
    var mc = new Hammer(this);
    var that = $(this);
    mc.on('swiperight', function (ev) {
      $(that).find('.pin').trigger('click');
    });
  });
  $('.content-close-pip, .content-maximize-pip').hide();
});

global.requestNewFeedItems = function () {
  $.get('/feed/refresh/', function (html) {
    $('.feed-list').prepend(html);
    $('.noFeedItems').html(html).addClass('feed-list').removeClass('noFeedItems');
    $('.js-form-feed').find("input[type=text], textarea").val("");
  });
};

function openPage(url, shareId, userFeedItemId) {
  var isMobile = $('.feed-list--type-sidebar').attr('data-is-mobile');
  var disableXcheck = $('.feed-list--type-sidebar').attr('data-hideXframe');
  $('.profileMenu').slideUp();

  if (isMobile === "1") {
    hasXFrameHeader(url, shareId);
  } else {
    if (disableXcheck === "1") {
      openInFrame(url, shareId);
    } else {
      hasXFrameHeader(url, shareId);
    }
  }

  setItemToOpened(userFeedItemId);
}

function hasXFrameHeader(url, shareId) {
  $.post('/feed/check-header/', {
    url: url
  }).then(function (data) {
    if (data.found === true) {
      openInNewWindow(url);
    } else {
      openInFrame(url, shareId);
    }
  });
}

function setItemToOpened(userFeedItemId) {
  $.post('/feed/set-opened/', {
    userFeedItemId: userFeedItemId
  });
}

function openInFrame(url, shareId) {
  $('.content').removeClass('hide-if-mobile');
  $('aside').addClass('hide-if-mobile');
  $('.Homepage').addClass('pageOpen');
  $('footer .defaultView').hide();
  $('footer .pageView').show();
  $('.content-frame').attr('src', parseUrl(url, true));
  $('.urlbar a').text('https://' + window.location.hostname + '/share/' + shareId).attr('href', url);
  $('.js-copy-to-clipboard').attr('data-clipboard-text', 'https://' + window.location.hostname + '/share/' + shareId);
}

function openInNewWindow(url) {
  window.open(url);

  if (!$('.feed-list--type-sidebar').attr('data-is-mobile')) {
    $('.content-frame').attr('src', '/feed/opened-in-popup/');
  }
}

function switchToPicutreInPicture() {
  $('.content-pictureInPictureFrame').remove();
  $('.content-frame').addClass('content-pictureInPictureFrame').removeClass('content-frame');
  createIframe('content-frame', '');
}

function openInPictureInPicture(url) {
  $('.content-pictureInPictureFrame').remove();
  createIframe('content-pictureInPictureFrame', url);
}

function switchFromPictureInPicture() {
  $('.content-frame').remove();
  $('.content-pictureInPictureFrame').addClass('content-frame').removeClass('content-pictureInPictureFrame');
  $('.content-close-pip').hide();
  $('.content-maximize-pip').hide();
}

function createIframe(className, url) {
  var newFrame = document.createElement('iframe');
  newFrame.className = className;

  if (location.protocol === 'https:') {
    url = url.replace('http://', 'https://');
  }

  newFrame.src = url;
  $(newFrame).attr('sandbox', 'allow-scripts allow-same-origin allow-forms allow-popups allow-pointer-lock allow-modals');
  $(newFrame).attr('allowfullscreen', 'allowfullscreen');
  $('.iFramesContainer').append(newFrame);
  $('.content-close-pip').show();
  $('.content-maximize-pip').show();
}

function parseUrl(url, changeColors) {
  var videoId = url.replace('https://www.youtube.com/watch?v=', '');

  if (url !== videoId) {
    if (changeColors) {
      $('.feed-list, .tabBar, .tabs').addClass('darkTheme', 2000, 'easeInOutQuad');
    }

    $('.header--bar, footer').css('backgroundColor', '#1a1a1a');
    return 'https://www.youtube.com/embed/' + videoId + '?autoplay=true';
  }

  if (changeColors) {
    $('.feed-list, .tabBar, .tabs').removeClass('darkTheme', '', 2000, 'easeInOutQuad');
  }

  if (location.protocol === 'https:') {
    url = url.replace('http://', 'https://');
  }

  return url;
}

function hexToRgb(hex) {
  // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
  var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
  hex = hex.replace(shorthandRegex, function (m, r, g, b) {
    return r + r + g + g + b + b;
  });
  var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
  return result ? {
    r: parseInt(result[1], 16),
    g: parseInt(result[2], 16),
    b: parseInt(result[3], 16)
  } : null;
}
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../../../../node_modules/webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./resources/js/legacy/components/register.js":
/*!****************************************************!*\
  !*** ./resources/js/legacy/components/register.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  $('.registerBox input[type="submit"]').on('click', function (e) {
    if (!$('.registerBox input[type="checkbox"]').is(':checked')) {
      e.preventDefault();
      alert('Registration can\'t continue, you must agree if you want to use Feednews.');
    }
  });
});

/***/ }),

/***/ "./resources/js/legacy/components/screensaver.js":
/*!*******************************************************!*\
  !*** ./resources/js/legacy/components/screensaver.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  if ($('.screensaver--newsticker-title').html()) {
    setInterval(function () {
      setTimeout(refreshPage, 5 * 1000);
    }, 3 * 60 * 1000);
    setInterval(updateTime, 1000);
    setInterval(updateWeather, 5 * 60 * 1000);
    setInterval(nextNewsItem, 20 * 1000);
    setTimeout(showItems, 3000);
    refreshPage();
    updateWeather();
  }
});
var currentNewsItemKey = 0;

function refreshPage() {
  /** global: Image */
  var newImage = new Image();
  var time = $.now();
  newImage.src = '/screensaver/images/' + time + '.jpg';

  newImage.onload = function () {
    $('.notActive').css('background-image', 'url("/screensaver/images/' + time + '.jpg")').fadeIn(3000);
    $('.active').fadeOut(3000);
    $('.notActive, .active').toggleClass('active notActive');
  };
}

function updateTime() {
  var now = new Date();
  var hours = now.getHours();
  var minutes = now.getMinutes();

  if (hours < 10) {
    hours = '0' + hours;
  }

  if (minutes < 10) {
    minutes = '0' + minutes;
  }

  $('.screensaver--time').html(hours + ':' + minutes);
}

function updateWeather() {
  $('.screensaver .weather').load('/weather/icon/');
}

function showItems() {
  $('.screensaver--newsticker,' + '.screensaver--time,' + '.screensaver--newsticker-source,' + '.screensaver--newsticker-title').fadeToggle('slow');
}

function nextNewsItem() {
  $('.screensaver--newsticker-source, .screensaver--newsticker-title').fadeToggle('slow');
  setTimeout(showNextNewsItem, 1000);
}

function showNextNewsItem() {
  currentNewsItemKey++;

  if (currentNewsItemKey > 29) {
    currentNewsItemKey = 0;
  }
  /** global: newsItems */


  $('.screensaver--newsticker-source').html(newsItems[currentNewsItemKey][0]).attr('class', 'screensaver--newsticker-source').fadeToggle('slow').css('backgroundColor', newsItems[currentNewsItemKey][3]);
  $('.screensaver--newsticker-title').html(newsItems[currentNewsItemKey][1] + '<span>' + newsItems[currentNewsItemKey][2] + '</span>').fadeToggle('slow');
}

/***/ }),

/***/ "./resources/js/legacy/components/searchbox.js":
/*!*****************************************************!*\
  !*** ./resources/js/legacy/components/searchbox.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  $('.js-search-feed').on('keyup', function () {
    searchFeeds($(this).val());
  });
});

var searchFeeds = function searchFeeds(searchQuery) {
  $('.js-search-list').html('').slideDown();

  if (searchQuery === '' || searchQuery.substring(0, 4) === 'http') {
    return;
  }

  $.getJSON('/feed/search/?query=' + encodeURIComponent(searchQuery), function (data) {
    if (data.status !== 'success') {
      return;
    }

    var source = $('#js-feed-item-template').html();
    /** global: Handlebars */

    var template = Handlebars.compile(source);
    $('.js-search-list').html(template({
      feedItems: data.data,
      query: searchQuery
    }));
  });
};

/***/ }),

/***/ "./resources/js/legacy/components/settings.js":
/*!****************************************************!*\
  !*** ./resources/js/legacy/components/settings.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  $('.js-settings-remove-feed').on('click', function () {
    var feedId = $(this).parent().parent().data('feed-id');
    var feedName = $(this).parent().parent().data('feed-name');
    var check = confirm('Are you sure you want to remove ' + feedName + '?');
    var button = $(this);

    if (check) {
      $.post("/settings/feeds/remove/", {
        feedId: feedId
      }).done(function () {
        $(button).parent().parent().addClass('removed');
      }).fail(function (data) {
        showDialog('Error', 'Cannot remove feed. Please try again later.<br /><br />' + data.toString().substr(0, 200));
      });
    }
  });
  $(".spectrum").spectrum({
    color: $(this).val(),
    allowEmpty: false,
    preferredFormat: "hex"
  });
  $('.js-settings-add-feed').on('click', function () {
    var url = $(this).parent().find("[name='url']").val();
    var color = $(this).parent().find("[name='color']").val();
    var icon = $(this).parent().find("[name='icon']").val();
    var website = $(this).parent().find("[name='website']").val();
    var autoPin = $(this).parent().find("[name='autoPin']").prop('checked');

    if (url.length === 0 && website.length === 0) {
      showDialog('Invalid input', 'Please enter a website url or RSS feed-url');
      return;
    }

    $.post("/settings/feeds/add/", {
      url: url,
      website: website,
      color: color,
      icon: icon,
      autoPin: !!autoPin
    }).done(function (data) {
      if (data.status === 'success') {
        location.reload();
      } else {
        showDialog('Cannot add Feed', 'An error occured while adding the feed:<br /><br />' + data.message.substr(0, 300));
      }
    }).fail(function (data) {
      showDialog('Cannot add Feed', 'An error occured while adding the feed:<br /><br />' + data.toString().substr(0, 300));
    });
  });
  $('.js-update-feed-color').on('change', function () {
    var newColor = $(this).val();
    var userFeedId = $(this).parent().parent().parent().data('feed-id');
    $.post("/settings/feeds/update/", {
      id: userFeedId,
      color: newColor
    }).done(function () {// Do nothing
    }).fail(function (data) {
      showDialog('Failed to update color', 'Your color setting cannot be changed at the moment because of a server error. Please try again later.<br /><br /><small>' + data.toString().substr(0, 200) + '</small>');
    });
  });
  $('.js-update-auto-pin').on('click', function () {
    var feedId = $(this).parent().parent().data('feed-id');
    var autoPin = 'on';
    var that = $(this);

    if (!$(this).prop('checked')) {
      autoPin = 'off';
    }

    $(that).hide();
    $.post("/settings/feeds/update/", {
      id: feedId,
      autoPin: autoPin
    }).done(function () {
      $(that).show();
    }).fail(function (data) {
      showDialog('Failed to update setting', 'Your auto pin setting cannot be changed at the moment because of a server error. Please try again later.<br /><br /><small>' + data.toString().substr(0, 200) + '</small>');
      $(that).show();
    });
  });
  var IconToBeReplaced;
  $('.js-open-icon-selector').on('click', function () {
    $('.iconSelector input').val($(this).parent().parent().data('feed-id'));
    $('.iconSelector').modal({
      fadeDuration: 100
    });
    IconToBeReplaced = $(this);
  });
  $('.iconSelector .fa, .iconSelector button').on('click', function () {
    var name = $(this).attr('class').replace('fa fa-', '');
    var id = $(this).parent().parent().find('input').val();

    if (name === '') {
      name = 'plus emptyIcon';
    }

    if (parseInt(id) > 0) {
      $.post("/settings/feeds/update/", {
        id: id,
        icon: name
      }).done(function () {
        $('.iconSelector input').val('');
        $('.iconSelector').hide();
        $('.jquery-modal').hide();
        $(IconToBeReplaced).attr('class', 'fa fa-' + name);
      }).fail(function (data) {
        showDialog('Failed to update setting', 'Your auto pin setting cannot be changed at the moment because of a server error. Please try again later.<br /><br /><small>' + data.toString().substr(0, 200) + '</small>');
      });
    }
  });
  $('.js-follow-feed').on('click', function () {
    var that = this;
    $.post("/settings/feeds/follow/", {
      feed_id: $(this).data('feed-id')
    }).done(function (data) {
      if (data.status === 'success') {
        $(that).html('Following').css('background-color', 'whitesmoke').css('color', 'gray');
        $('.refreshNotice').show();
      } else {
        showDialog('Cannot add Feed', 'An error occured while adding the feed:<br /><br />' + data.message.substr(0, 300));
      }
    }).fail(function (data) {
      showDialog('Cannot add Feed', 'An error occured while adding the feed:<br /><br />' + data.toString().substr(0, 300));
    });
  });
});

/***/ }),

/***/ "./resources/js/legacy/components/tabs.js":
/*!************************************************!*\
  !*** ./resources/js/legacy/components/tabs.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  $('.tabBar button').on('click', function () {
    switchToTab(this);
  });
});

function switchToTab(el) {
  var name = $(el).data('open-tab');
  $('.tabBar button').removeClass('active');
  $(el).addClass('active');
  $('.tabs .tab').hide();
  $('.tab--' + name).show();

  if (name == 'history') {
    loadHistory();
  }
}

function loadHistory() {
  var source = $('#js-feed-item-template').html();
  /** global: Handlebars */

  var template = Handlebars.compile(source);
  $.getJSON('/feed/opened/', function (data) {
    if (data.status !== 'success') {
      return;
    }

    $('.tab--history').html(template({
      feedItems: data.items
    }));
  });
}

/***/ }),

/***/ "./resources/js/legacy/components/welcome.js":
/*!***************************************************!*\
  !*** ./resources/js/legacy/components/welcome.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  $(document).on('click', '.widget-custom-item', function () {
    var url = $(this).data('url');

    if (url === '') {
      url = '/nourl/';
    }
    /** global: parent */


    parent.$('.content-frame').attr('src', url);
    parent.$('.urlbar a').text(url).attr('href', url);
    parent.$('.js-copy-to-clipboard').attr('data-clipboard-text', url).addClass('show-if-mobile');
  });
  $('.weather--content').load('/weather/detail/');
});

/***/ }),

/***/ "./resources/sass/app.scss":
/*!*********************************!*\
  !*** ./resources/sass/app.scss ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!*************************************************************!*\
  !*** multi ./resources/js/app.js ./resources/sass/app.scss ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! /mnt/c/xampp/htdocs/feednews/resources/js/app.js */"./resources/js/app.js");
module.exports = __webpack_require__(/*! /mnt/c/xampp/htdocs/feednews/resources/sass/app.scss */"./resources/sass/app.scss");


/***/ })

/******/ });