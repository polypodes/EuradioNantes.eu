////////////////////////////////////////
// Podcast js                         //
////////////////////////////////////////

////////////////////////////////////////
// Requirements                       //
////////////////////////////////////////
import {
  select,
  selectAll,
  each,
  on,
  findNode
} from '../util/util';

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
  const className = 'podcast-item-control';
  const node = event.target.parentElement.children;
  const $buttonPlayerControl = findNode(node, className);

  togglePodcastState($buttonPlayerControl);
}

function initPlayer($player) {
  const $button = $player
      .querySelector('.podcast-item-control');
  const $audioElement = $player
    .querySelector('.podcast-item-player');

  // click
  $button.addEventListener('click', (event) => {
    togglePodcastState(event.target, $audioElement);
  });

  // ended event
  $audioElement.addEventListener('ended', (event) => {
    $button.classList.remove('podcast-item-pause');
    $button.classList.add('podcast-item-play');
  });
};

function secondsToMinutes(_seconds) {
  const minutes = Math.floor(_seconds / 60);
  // force two digits format 00 to 09
  const seconds = ('0' + (_seconds - minutes * 60)).slice(-2);
  return `${minutes}:${seconds}s`;
};

function addAnnotation($parent, title, text, startAt) {
  const timeFormated = secondsToMinutes(startAt);

  $parent.querySelector('.podcast-player-list').innerHTML += `
    <div data-annotation-start="${startAt}"
      data-annotation-title="${title}"
      data-annotation-text="${text}"
      class="podcast-player-list-item">
      <span class="podcast-player-list-start">
        à ${timeFormated}
      </span>
      <button class="
        podcast-item-control
        podcast-item-play
        podcast-player-list-play">
      </button>
      <span class="podcast-player-list-title">${title}</span>
    </div>
  `;
}

function initPlayer($player) {
  const wavesurfer = Object.create(WaveSurfer);
  const source = $player.getAttribute('data-player-src');
  let data = $player.getAttribute('data-player-regions');
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
    const $annotation = document.createElement('div');
    $annotation.classList.add('podcast-player-bloc');
    $annotation.innerHTML = `
      <div class="podcast-player-list">
      </div>
      <div class="podcast-player-info">
        <h3 class="podcast-player-title"></h3>
        <p class="podcast-player-text"></p>
      </div>
    `;

    if (data.regions.length > 0) {
      $player.appendChild($annotation);

      // display each region
      data.regions.map((region) => {
        wavesurfer.addRegion(region);
        // add related annotation
        const title = region.data.title;
        const text = region.data.text;
        const startAt = region.start;
        addAnnotation($player, title, text, startAt);
      });
    }

    // button
    const $button = $player.querySelector('.podcast-item-control');
    $button.addEventListener('click', () => {
      if (!wavesurfer.isPlaying()) {
        wavesurfer.play();
      } else {
        wavesurfer.pause();
      }
    });

    // list comment
    const $items = $player.querySelectorAll('.podcast-player-list-item');

    for (let i = 0; i < $items.length; i++) {
      $items[i].addEventListener('click', function (event) {
        const $elt = event.target;
        let startAt = 0;
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
    const title = region.data.title;
    const text = region.data.text;
    const $title = $player.querySelector('.podcast-player-title');
    const $text = $player.querySelector('.podcast-player-text');
    const $item = $player.querySelector(`[data-annotation-title="${region.data.title}"]`);
    const $items = $player.querySelectorAll('[data-annotation-title]');

    // reset class
    for (let i = 0; i < $items.length; i++) {
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
  // const $players = selectAll('.podcast-item');

  // each($players, initPlayer);

  ////////////////////////////////////////
  // Wavesurfer                         //
  ////////////////////////////////////////

  const players = document.querySelectorAll('.podcast-player');

  for (let i = 0; i < players.length; i++) {
    initPlayer(players[i]);
  }
}

export default init;
