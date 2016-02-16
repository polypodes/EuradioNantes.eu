////////////////////////////////////////
// Ecoute js                         //
////////////////////////////////////////

////////////////////////////////////////
// Requirements                       //
////////////////////////////////////////
import {selectAll, each, on, addClass, removeClass, hasClass} from '../util/util';

////////////////////////////////////////////////////////////////////////////////
// Helpers & private methods          //
////////////////////////////////////////

function openLinksInOpener() {
  var links = document.querySelectorAll('a');
  Array.prototype.forEach.call(links, function(el, i){
    el.addEventListener('click', function(e) {
      if (window.opener) {
        e.preventDefault();
        window.opener.location.href = e.target.getAttribute('href');
      }
    })
  });
}

function initPlayer() {

  var player = document.getElementById('player'),
      playerControl = document.getElementById('player-control'),
      playButton = selectAll('.player-play')[0],
      pauseButton = selectAll('.player-pause')[0]
  ;

  var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
  
  if(iOS) {
    console.log('IOS');
    playerControl.dispatchEvent(new MouseEvent('click'));
  }

  playerControl.addEventListener('click', function(e) {
    if (player.paused){
      player.play();
    } else {
      player.pause();
    }
  });

  player.addEventListener('playing', function(e) {
    console.log('playing');
    removeClass(pauseButton, 'player-hide');
    addClass(playButton, 'player-hide');
  });
  player.addEventListener('pause', function(e) {
    console.log('paused');
    removeClass(playButton, 'player-hide');
    addClass(pauseButton, 'player-hide');
  });

}

var dispatchMouseEvent = function(target, var_args) {
  var e = document.createEvent("MouseEvents");
  // If you need clientX, clientY, etc., you can call
  // initMouseEvent instead of initEvent
  e.initEvent.apply(e, Array.prototype.slice.call(arguments, 1));
  target.dispatchEvent(e);
};

////////////////////////////////////////
// Public API                         //
////////////////////////////////////////

/**
 * common function example
 * this is a trivial example of function
 */
function init() {
  if (selectAll('.walkman').length === 0) return;

  openLinksInOpener();

  document.addEventListener('DOMContentLoaded', function () {
    initPlayer();
  });

  setInterval(function() {
    //openLinksInOpener();
    var request = new XMLHttpRequest();
    request.open('GET', this.location.href, true);
    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    request.onload = function() {
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

    request.onerror = function() {
      // There was a connection error of some sort
    };

    request.send();
  }, 10000);
}

export default init;
