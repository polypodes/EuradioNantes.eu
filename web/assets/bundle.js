/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};

/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {

/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;

/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			exports: {},
/******/ 			id: moduleId,
/******/ 			loaded: false
/******/ 		};

/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);

/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;

/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}


/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;

/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;

/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";

/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ function(module, exports, __webpack_require__) {

	// modules
	'use strict';

	function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

	var _basesCommonJs = __webpack_require__(1);

	var _basesCommonJs2 = _interopRequireDefault(_basesCommonJs);

	var _headerHeaderJs = __webpack_require__(3);

	var _headerHeaderJs2 = _interopRequireDefault(_headerHeaderJs);

	var _blocBlocJs = __webpack_require__(4);

	var _blocBlocJs2 = _interopRequireDefault(_blocBlocJs);

	var _podcastPodcastJs = __webpack_require__(5);

	var _podcastPodcastJs2 = _interopRequireDefault(_podcastPodcastJs);

	var _emissionEmissionJs = __webpack_require__(6);

	var _emissionEmissionJs2 = _interopRequireDefault(_emissionEmissionJs);

	var _programProgramJs = __webpack_require__(7);

	var _programProgramJs2 = _interopRequireDefault(_programProgramJs);

	var _programEcouteJs = __webpack_require__(8);

	var _programEcouteJs2 = _interopRequireDefault(_programEcouteJs);

	var _shareShareJs = __webpack_require__(9);

	var _shareShareJs2 = _interopRequireDefault(_shareShareJs);

	////////////////////////////////////////
	// Dependencies init                  //
	////////////////////////////////////////

	[_basesCommonJs2['default'], _headerHeaderJs2['default'], _blocBlocJs2['default'], _podcastPodcastJs2['default'], _emissionEmissionJs2['default'], _programProgramJs2['default'], _programEcouteJs2['default'], _shareShareJs2['default']
	// append here your dependencies

	].map(function (dependency) {
	  return dependency();
	});

	////////////////////////////////////////
	// below - shit to remove in prod     //
	////////////////////////////////////////

/***/ },
/* 1 */
/***/ function(module, exports, __webpack_require__) {

	////////////////////////////////////////
	// Common stuffs                      //
	////////////////////////////////////////

	////////////////////////////////////////
	// Requirements                       //
	////////////////////////////////////////
	// import $ from 'jquery';
	'use strict';

	Object.defineProperty(exports, '__esModule', {
	  value: true
	});

	var _utilUtil = __webpack_require__(2);

	////////////////////////////////////////
	// Helpers & private methods          //
	////////////////////////////////////////

	/**
	 * common helper function example
	 * this is a trivial example
	 */
	// function genUuid() {
	//   // ...
	// }

	////////////////////////////////////////
	// Public API                         //
	////////////////////////////////////////

	/**
	 * common function example
	 * this is a trivial example of function
	 */
	function common() {
	  // on air (in header)
	  var elements = (0, _utilUtil.selectAll)('.player-svg, .player-what');

	  if (elements.length > 0) {
	    (0, _utilUtil.each)(elements, function (el, i) {
	      el.addEventListener('click', function (e) {
	        e.preventDefault();
	        if (ecoutePath) {
	          window.open(ecoutePath, 'Le direct - Euradionantes', 'width=1010,height=495,left=320,top=445');
	        }
	      });
	    });
	  }
	}

	exports['default'] = common;
	module.exports = exports['default'];

/***/ },
/* 2 */
/***/ function(module, exports) {

	////////////////////////////////////////
	// Util : set of usefull function     //
	////////////////////////////////////////

	////////////////////////////////////////
	// Requirements                       //
	////////////////////////////////////////
	// import $ from 'jquery';

	////////////////////////////////////////
	// Helpers                            //
	////////////////////////////////////////

	////////////////////////////////////////
	// private properties                 //
	////////////////////////////////////////

	////////////////////////////////////////
	// private methods                    //
	////////////////////////////////////////

	////////////////////////////////////////
	// Public API                         //
	////////////////////////////////////////

	/**
	 * select the first matched element
	 * @param  {string} selector: css selector
	 * @return {elementHTML} html node
	 */
	'use strict';

	Object.defineProperty(exports, '__esModule', {
	  value: true
	});
	function select(selector) {
	  return document.querySelector(selector);
	}

	/**
	 * select all matched element
	 * @param  {string} selector: css selector
	 * @return {array[elementHTML]} an array of html node
	 */
	function selectAll(selector) {
	  return document.querySelectorAll(selector);
	}

	function cl(msg) {
	  console.log(msg);
	}

	function each(array, callback) {
	  for (var i = 0; i < array.length; i++) {
	    callback(array[i]);
	  }
	}

	/**
	 * find a node in HTMLCollection of node
	 * @param  {HTMLCollection} HTMLCollection
	 * @param  {string} className: ex: Lorem-ipsum (without dot)
	 * @return {HTMLnode || false} If node found it return
	 * the node else it return false
	 */
	function findNode(HTMLCollection, className) {
	  for (var i = HTMLCollection.length - 1; i >= 0; i--) {
	    if (HTMLCollection[i].classList.contains(className)) {
	      return HTMLCollection[i];
	    }
	  }

	  return false;
	};

	/**
	 * equivalent of jquery 'ready'
	 * @param  {Function} callback
	 * @return {void}
	 */
	function documentReady(callback) {
	  if (document.readyState !== 'loading') {
	    callback();
	  } else {
	    document.addEventListener('DOMContentLoaded', callback);
	  }
	}

	/**
	 * on: jquery equivalent of .on({event}, {fn})
	 * @param  {elementHTML}    elt: an HTML node
	 * @param  {Function}  callback
	 * @param  {...[string]} events: an event or
	 * a list of event (ex: click, touchstart)
	 * @return {void}
	 */
	function on(elt, callback) {
	  var _this = this;

	  /*eslint-disable */
	  if (!elt) return;

	  for (var _len = arguments.length, events = Array(_len > 2 ? _len - 2 : 0), _key = 2; _key < _len; _key++) {
	    events[_key - 2] = arguments[_key];
	  }

	  events.map(function (event) {
	    return elt.addEventListener(event, callback.bind(_this, elt));
	  });
	  /*eslint-enable */
	}

	/**
	 * remove a given css class from an element
	 * @param  {elementHTML} elem element
	 * @param  {string} cn   class name
	 * @return {void}
	 */
	function removeClass(elem, cn) {
	  if (elem.classList) {
	    elem.classList.remove(cn);
	  } else {
	    elem.className = elem.className.replace(new RegExp('(^|\\b)' + cn.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
	  }
	}

	/**
	 * add a given css class on an element
	 * @param  {elementHTML} elem element
	 * @param  {string} cn   class name
	 * @return {void}
	 */
	function addClass(elem, cn) {
	  if (elem.classList) elem.classList.add(cn);else elem.className += ' ' + cn;
	}

	function hasClass(elem, cn) {
	  return elem.classList && elem.classList.contains(cn) || new RegExp('(^| )' + cn + '( |$)', 'gi').test(elem.className);
	}

	exports['default'] = {
	  cl: cl,
	  documentReady: documentReady,
	  each: each,
	  on: on,
	  select: select,
	  selectAll: selectAll,
	  findNode: findNode,
	  addClass: addClass,
	  removeClass: removeClass,
	  hasClass: hasClass
	};
	module.exports = exports['default'];

/***/ },
/* 3 */
/***/ function(module, exports, __webpack_require__) {

	////////////////////////////////////////
	// Header                             //
	////////////////////////////////////////

	////////////////////////////////////////
	// Requirements                       //
	////////////////////////////////////////
	'use strict';

	Object.defineProperty(exports, '__esModule', {
	  value: true
	});

	var _utilUtil = __webpack_require__(2);

	////////////////////////////////////////
	// Helpers                            //
	////////////////////////////////////////

	////////////////////////////////////////
	// private properties                 //
	////////////////////////////////////////
	var $menuBtn = (0, _utilUtil.select)('.header-menu-button');
	var $menu = (0, _utilUtil.select)('.header-menu');
	var $menuItems = (0, _utilUtil.select)('.header-items');
	var $linkWithChildren = (0, _utilUtil.selectAll)('.header-link-has-children');
	var $goBackBtn = (0, _utilUtil.select)('.header-menu-back');
	var $level2 = (0, _utilUtil.selectAll)('.header-menu ul > li > ul');

	////////////////////////////////////////
	// private methods                    //
	////////////////////////////////////////

	/**
	 * triggered when document is ready
	 * @return {void}
	 */
	function documentLoaded() {

	  (0, _utilUtil.on)($menuBtn, toggleMenu, 'click');
	  (0, _utilUtil.on)($goBackBtn, hideLevel2, 'click');

	  (0, _utilUtil.each)($linkWithChildren, function ($elt) {
	    (0, _utilUtil.on)($elt, toggleLevel2, 'click');
	    $elt.addEventListener('click', toggleLevel2);
	  });
	}

	/**
	 * show/hide menu mobile
	 * @return {void}
	 */
	function toggleMenu() {
	  if ((0, _utilUtil.select)('.header-menu-show') !== null) {
	    $menu.classList.add('header-menu-hide');
	    $menu.classList.remove('header-menu-show');
	    (0, _utilUtil.select)('body').classList.remove('header-menu-opened');
	  } else {
	    $menu.classList.remove('header-menu-hide');
	    $menu.classList.add('header-menu-show');
	    (0, _utilUtil.select)('body').classList.add('header-menu-opened');
	  }

	  hideLevel2();
	}

	/**
	 * show second level of menu mobile
	 * @param  {event} event from addEventListener
	 * @return {void}
	 */
	function toggleLevel2(event) {
	  event.preventDefault();

	  var $li = event.target.parentNode;
	  var $ul = $li.querySelector('ul');

	  $ul.classList.toggle('header-level2-show');
	  $menuItems.classList.toggle('header-level2');

	  // show go back button
	  $goBackBtn.classList.toggle('header-menu-back-show');

	  var hideUL = function hideUL() {
	    $ul.classList.remove('header-level2-show');
	    $goBackBtn.classList.remove('header-menu-back-show');

	    document.removeEventListener('click', hideUL);
	  };

	  // close panel on click (desktop)
	  if ((0, _utilUtil.select)('.header-level2-show') !== null) {
	    setTimeout(function () {
	      document.addEventListener('click', hideUL);
	    }, 100);
	  }
	}

	/**
	 * hide second level of menu mobile
	 * @return {void}
	 */
	function hideLevel2() {
	  (0, _utilUtil.each)($level2, function ($elt) {
	    $elt.classList.remove('header-level2-show');
	  });
	  $menuItems.classList.remove('header-level2');

	  // hide go back button
	  $goBackBtn.classList.remove('header-menu-back-show');
	}

	////////////////////////////////////////
	// Public API                         //
	////////////////////////////////////////

	/**
	 * init function
	 * this is a trivial init function
	 */
	function init() {
	  (0, _utilUtil.documentReady)(documentLoaded);
	}

	exports['default'] = init;
	module.exports = exports['default'];

/***/ },
/* 4 */
/***/ function(module, exports, __webpack_require__) {

	////////////////////////////////////////
	// Bloc js                            //
	////////////////////////////////////////

	////////////////////////////////////////
	// Requirements                       //
	////////////////////////////////////////
	'use strict';

	Object.defineProperty(exports, '__esModule', {
	  value: true
	});

	var _utilUtil = __webpack_require__(2);

	////////////////////////////////////////
	// Helpers & private methods          //
	////////////////////////////////////////

	/**
	 * common helper function example
	 * this is a trivial example
	 */
	function changeLocation(event, $elt) {
	  var $url = $elt.querySelector('[data-clickable-target="true"]');
	  var path = $url.getAttribute('href');
	  location.pathname = path;
	}

	////////////////////////////////////////
	// Public API                         //
	////////////////////////////////////////

	/**
	 * common function example
	 * this is a trivial example of function
	 */
	function init() {
	  var $clickableElts = (0, _utilUtil.selectAll)('[data-clickable="true"]');

	  if ($clickableElts.lenght === 0) return;

	  (0, _utilUtil.each)($clickableElts, function ($elt) {
	    $elt.addEventListener('click', changeLocation.bind(null, event, $elt), 'true');
	  });
	}

	exports['default'] = init;
	module.exports = exports['default'];

/***/ },
/* 5 */
/***/ function(module, exports, __webpack_require__) {

	////////////////////////////////////////
	// Podcast js                         //
	////////////////////////////////////////

	////////////////////////////////////////
	// Requirements                       //
	////////////////////////////////////////
	'use strict';

	Object.defineProperty(exports, '__esModule', {
	  value: true
	});

	var _utilUtil = __webpack_require__(2);

	////////////////////////////////////////
	// Helpers & private methods          //
	////////////////////////////////////////

	/**
	 * common helper function example
	 * this is a trivial example
	 */
	function togglePodcastState($elt, $player) {
	  if (!$player.paused) {
	    $elt.classList.remove('podcast-item-pause');
	    $elt.classList.add('podcast-item-play');
	    $player.pause();
	  } else {
	    $elt.classList.remove('podcast-item-play');
	    $elt.classList.add('podcast-item-pause');
	    $player.play();
	  }
	}

	/**
	 * triggered at end of playing
	 * @param  {event} event]
	 * @return {void}
	 */
	function handleEndOfPlaying(event) {
	  var className = 'podcast-item-control';
	  var node = event.target.parentElement.children;
	  var $buttonPlayerControl = (0, _utilUtil.findNode)(node, className);

	  togglePodcastState($buttonPlayerControl);
	}

	function initPlayer($player) {
	  var $button = $player.querySelector('.podcast-item-control');
	  var $audioElement = $player.querySelector('.podcast-item-player');

	  // click
	  $button.addEventListener('click', function (event) {
	    togglePodcastState(event.target, $audioElement);
	  });

	  // ended event
	  $audioElement.addEventListener('ended', function (event) {
	    $button.classList.remove('podcast-item-pause');
	    $button.classList.add('podcast-item-play');
	  });
	};

	function secondsToMinutes(_seconds) {
	  var minutes = Math.floor(_seconds / 60);
	  // force two digits format 00 to 09
	  var seconds = ('0' + (_seconds - minutes * 60)).slice(-2);
	  return minutes + ':' + seconds + 's';
	};

	function addAnnotation($parent, title, text, startAt) {
	  var timeFormated = secondsToMinutes(startAt);

	  $parent.querySelector('.podcast-player-list').innerHTML += '\n    <div data-annotation-start="' + startAt + '"\n      data-annotation-title="' + title + '"\n      data-annotation-text="' + text + '"\n      class="podcast-player-list-item">\n      <span class="podcast-player-list-start">\n        à ' + timeFormated + '\n      </span>\n      <button class="\n        podcast-item-control\n        podcast-item-play\n        podcast-player-list-play">\n      </button>\n      <span class="podcast-player-list-title">' + title + '</span>\n    </div>\n  ';
	}

	function initPlayer($player) {
	  var wavesurfer = Object.create(WaveSurfer);
	  var source = $player.getAttribute('data-player-src');
	  var data = $player.getAttribute('data-player-regions');
	  data = JSON.parse(data);

	  wavesurfer.init({
	    container: $player,
	    waveColor: '#EB1D5D',
	    progressColor: '#C42456',
	    // progressColor: '#C42456',
	    cursorColor: '#282245',
	    backend: 'MediaElement',
	    height: 90,
	    minimap: true
	  });

	  wavesurfer.on('ready', function () {

	    // construct annotation list
	    var $annotation = document.createElement('div');
	    $annotation.classList.add('podcast-player-bloc');
	    $annotation.innerHTML = '\n      <div class="podcast-player-list">\n      </div>\n      <div class="podcast-player-info">\n        <h3 class="podcast-player-title"></h3>\n        <p class="podcast-player-text"></p>\n      </div>\n    ';

	    $player.appendChild($annotation);

	    // display each region
	    data.regions.map(function (region) {
	      wavesurfer.addRegion(region);
	      // add related annotation
	      var title = region.data.title;
	      var text = region.data.text;
	      var startAt = region.start;
	      addAnnotation($player, title, text, startAt);
	    });

	    // button
	    var $button = $player.querySelector('.podcast-item-control');
	    $button.addEventListener('click', function () {
	      if (!wavesurfer.isPlaying()) {
	        wavesurfer.play();
	      } else {
	        wavesurfer.pause();
	      }
	    });

	    // list comment
	    var $items = $player.querySelectorAll('.podcast-player-list-item');

	    for (var i = 0; i < $items.length; i++) {
	      $items[i].addEventListener('click', function (event) {
	        var $elt = event.target;
	        var startAt = 0;
	        if (typeof $elt.getAttribute('data-annotation-start') === 'object') {
	          startAt = parseInt($elt.parentElement.getAttribute('data-annotation-start'));
	        } else {
	          startAt = parseInt($elt.getAttribute('data-annotation-start'));
	        }
	        wavesurfer.play(startAt);
	      });
	    }

	    wavesurfer.on('play', function () {
	      $button.classList.remove('podcast-item-play');
	      $button.classList.add('podcast-item-pause');
	    });
	    wavesurfer.on('pause', function () {
	      $button.classList.add('podcast-item-play');
	      $button.classList.remove('podcast-item-pause');
	    });
	  });

	  // display info when ok
	  wavesurfer.on('region-click', function (region, e) {
	    e.stopPropagation();
	    region.play();
	  });

	  wavesurfer.on('region-in', showNote);

	  function showNote(region) {
	    var title = region.data.title;
	    var text = region.data.text;
	    var $title = $player.querySelector('.podcast-player-title');
	    var $text = $player.querySelector('.podcast-player-text');
	    var $item = $player.querySelector('[data-annotation-title="' + region.data.title + '"]');
	    var $items = $player.querySelectorAll('[data-annotation-title]');

	    // reset class
	    for (var i = 0; i < $items.length; i++) {
	      $items[i].classList.remove('podcast-player-item-active');
	    }

	    $item.classList.add('podcast-player-item-active');

	    // scroll to active item
	    $player.querySelector('.podcast-player-list').scrollTop = $item.offsetTop - 90;
	    $title.innerHTML = title;
	    $text.innerHTML = text;

	    // if (!showNote.el) {
	    //   showNote.el = document.querySelector('#subtitle');
	    // }
	    // showNote.el.textContent = region.data.text;
	  }

	  wavesurfer.load(source);
	}

	////////////////////////////////////////
	// Public API                         //
	////////////////////////////////////////

	/**
	 * common function example
	 * this is a trivial example of function
	 */
	function init() {
	  var $players = (0, _utilUtil.selectAll)('.podcast-item');

	  (0, _utilUtil.each)($players, initPlayer);

	  ////////////////////////////////////////
	  // Wavesurfer                         //
	  ////////////////////////////////////////

	  var players = document.querySelectorAll('.podcast-player');

	  for (var i = 0; i < players.length; i++) {
	    initPlayer(players[i]);
	  }
	}

	exports['default'] = init;
	module.exports = exports['default'];

/***/ },
/* 6 */
/***/ function(module, exports, __webpack_require__) {

	////////////////////////////////////////
	// Emission js                         //
	////////////////////////////////////////

	////////////////////////////////////////
	// Requirements                       //
	////////////////////////////////////////
	'use strict';

	Object.defineProperty(exports, '__esModule', {
	    value: true
	});

	var _utilUtil = __webpack_require__(2);

	////////////////////////////////////////////////////////////////////////////////
	// Helpers & private methods          //
	////////////////////////////////////////

	////////////////////////////////////////
	// Public API                         //
	////////////////////////////////////////

	/**
	 * common function example
	 * this is a trivial example of function
	 */
	function navSelect() {
	    var $dirty = (0, _utilUtil.select)("#emission-quickmenu");
	    if ($dirty) {
	        $dirty.addEventListener('change', function () {
	            window.location.pathname = this.options[this.selectedIndex].getAttribute('data-href');
	        });
	    }
	}

	exports['default'] = navSelect;
	module.exports = exports['default'];

/***/ },
/* 7 */
/***/ function(module, exports, __webpack_require__) {

	////////////////////////////////////////
	// Program js                         //
	////////////////////////////////////////

	////////////////////////////////////////
	// Requirements                       //
	////////////////////////////////////////
	'use strict';

	Object.defineProperty(exports, '__esModule', {
	  value: true
	});

	var _utilUtil = __webpack_require__(2);

	////////////////////////////////////////////////////////////////////////////////
	// Helpers & private methods          //
	////////////////////////////////////////

	function updatePanels(panelId) {
	  var panels = (0, _utilUtil.selectAll)('.program-tab');

	  (0, _utilUtil.each)(panels, function (el, i) {
	    if (el.id == panelId) {
	      el.style.display = '';
	      document.getElementById('grille-day').innerHTML = el.getAttribute('data-date');
	    } else {
	      el.style.display = 'none';
	    }
	  });
	}

	////////////////////////////////////////
	// Public API                         //
	////////////////////////////////////////

	/**
	 * common function example
	 * this is a trivial example of function
	 */
	function init() {
	  if ((0, _utilUtil.selectAll)('.program-tab').length === 0) return;

	  var elements = (0, _utilUtil.selectAll)('.actu-list-filter'),
	      className = 'actu-list-filter-selected';
	  var active = (0, _utilUtil.selectAll)('.actu-list-filter-selected')[0];

	  (0, _utilUtil.each)(elements, function (el, i) {
	    var links = el.querySelectorAll('a');
	    var contentId = links[0].getAttribute('href').substr(1);
	    var contentElem = document.getElementById(contentId);

	    // if elem has className, show panel, else hide it
	    if (contentElem) {
	      if ((0, _utilUtil.hasClass)(el, className)) {
	        contentElem.style.display = '';
	      } else {
	        contentElem.style.display = 'none';
	      }
	    }

	    links[0].addEventListener('click', function (e) {
	      var contentId = this.getAttribute('href').substr(1);

	      // remove className
	      //if (active.classList) { active.classList.remove(className); }
	      //else { active.className = active.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' '); }
	      (0, _utilUtil.removeClass)(active, className);

	      // addActive
	      active = e.target.parentNode;
	      (0, _utilUtil.addClass)(active, className);

	      updatePanels(contentId);
	      window.location.hash = '#' + contentId;
	      e.preventDefault();
	    });
	  });

	  // check if tab is selected in url hash
	  if (location.hash.match(/^#tab[0-9]/)) {
	    //removeClass(active, className);
	    var link = document.querySelectorAll('a[href="' + location.hash + '"]')[0];
	    link.click();
	    //active = elem.parentNode;
	  } else {
	      var num = dayNumber || 0;
	      var link = document.querySelectorAll('.actu-list-filter')[num].querySelectorAll('a')[0];
	      link.click();
	    }
	}

	exports['default'] = init;
	module.exports = exports['default'];

/***/ },
/* 8 */
/***/ function(module, exports, __webpack_require__) {

	////////////////////////////////////////
	// Ecoute js                         //
	////////////////////////////////////////

	////////////////////////////////////////
	// Requirements                       //
	////////////////////////////////////////
	'use strict';

	Object.defineProperty(exports, '__esModule', {
	  value: true
	});

	var _utilUtil = __webpack_require__(2);

	////////////////////////////////////////////////////////////////////////////////
	// Helpers & private methods          //
	////////////////////////////////////////

	function openLinksInOpener() {
	  var links = document.querySelectorAll('a');
	  Array.prototype.forEach.call(links, function (el, i) {
	    el.addEventListener('click', function (e) {
	      if (window.opener) {
	        e.preventDefault();
	        window.opener.location.href = e.target.getAttribute('href');
	      }
	    });
	  });
	}

	function initPlayer() {

	  var player = document.getElementById('player'),
	      playerControl = document.getElementById('player-control'),
	      playButton = (0, _utilUtil.selectAll)('.player-play')[0],
	      pauseButton = (0, _utilUtil.selectAll)('.player-pause')[0];
	  playerControl.addEventListener('click', function (e) {
	    if (player.paused) {
	      player.play();
	    } else {
	      player.pause();
	    }
	  });

	  player.addEventListener('playing', function (e) {
	    console.log('playing');
	    (0, _utilUtil.removeClass)(pauseButton, 'player-hide');
	    (0, _utilUtil.addClass)(playButton, 'player-hide');
	  });
	  player.addEventListener('pause', function (e) {
	    console.log('paused');
	    (0, _utilUtil.removeClass)(playButton, 'player-hide');
	    (0, _utilUtil.addClass)(pauseButton, 'player-hide');
	  });
	}

	////////////////////////////////////////
	// Public API                         //
	////////////////////////////////////////

	/**
	 * common function example
	 * this is a trivial example of function
	 */
	function init() {
	  if ((0, _utilUtil.selectAll)('.walkman').length === 0) return;

	  openLinksInOpener();

	  initPlayer();

	  setInterval(function () {
	    //openLinksInOpener();
	    var request = new XMLHttpRequest();
	    request.open('GET', this.location.href, true);
	    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

	    request.onload = function () {
	      if (request.status >= 200 && request.status < 400) {
	        // Success!
	        var content = request.responseText;
	        document.getElementById('main-content').innerHTML = content;
	        openLinksInOpener();
	      } else {
	        // We reached our target server, but it returned an error
	        if (console) console.log('Impossible de mettre à jour la page ', request);
	      }
	    };

	    request.onerror = function () {
	      // There was a connection error of some sort
	    };

	    request.send();
	  }, 10000);
	}

	exports['default'] = init;
	module.exports = exports['default'];

/***/ },
/* 9 */
/***/ function(module, exports, __webpack_require__) {

	////////////////////////////////////////
	// Share js                         //
	////////////////////////////////////////

	////////////////////////////////////////
	// Requirements                       //
	////////////////////////////////////////
	'use strict';

	Object.defineProperty(exports, '__esModule', {
	  value: true
	});

	var _utilUtil = __webpack_require__(2);

	////////////////////////////////////////////////////////////////////////////////
	// Helpers & private methods          //
	////////////////////////////////////////

	////////////////////////////////////////
	// Public API                         //
	////////////////////////////////////////

	/**
	 * common function example
	 * this is a trivial example of function
	 */
	function init() {
	  var shareLinks = (0, _utilUtil.selectAll)('.share-link'),
	      embed = document.getElementById('embed-code');

	  if (shareLinks.length === 0) return;

	  (0, _utilUtil.each)(shareLinks, function (el, i) {
	    el.addEventListener('click', function (e) {
	      e.preventDefault();
	      window.open(this.getAttribute('href'), this.getAttribute('title'), 'width=495,height=495,left=320,top=445');
	    });
	  });

	  if (embed) {
	    // show / hide embed code
	    embed.style.display = 'none';
	    document.getElementById('share-embeded').addEventListener('click', function (e) {
	      e.preventDefault();

	      if (embed.style.display == 'none') {
	        embed.style.display = '';
	      } else {
	        embed.style.display = 'none';
	      }
	    });
	  }
	}

	exports['default'] = init;
	module.exports = exports['default'];

/***/ }
/******/ ]);