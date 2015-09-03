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
  const seconds = ('0' + (_seconds - minutes * 60)).slice(-2); // force two digits format 00 to 09
  return `${minutes}:${seconds}s`;
};

function addAnnotation($parent, title, text, startAt) {
  const timeFormated = secondsToMinutes(startAt);
  console.log(startAt);
  $parent.querySelector('.podcast-player-list').innerHTML += `
    <div data-annotation-start="${startAt}" data-annotation-title="${title}" data-annotation-text="${text}" class="podcast-player-list-item">
      <span class="podcast-player-list-start">à ${timeFormated}</span>
      <button class="podcast-item-control podcast-item-play podcast-player-list-play"></button>
      <span class="podcast-player-list-title">${title}</span>
    </div>
  `;
}

function initPlayer($player) {
  let wavesurfer = Object.create(WaveSurfer);
  let source = $player.getAttribute('data-player-src');
  let data = JSON.parse($player.getAttribute('data-player-regions'));

  // console.log($player);
  // console.log(source);
  // console.log(data);

  wavesurfer.init({
    container: $player,
    waveColor: '#EB1D5D',
    progressColor: '#C42456',
    // progressColor: '#C42456',
    cursorColor: '#282245',
    height: 90,
    minimap: true
  });

  wavesurfer.on('ready', function () {
    console.log('ready');

    // construct annotation list
    let $annotation = document.createElement('div');
    $annotation.classList.add('podcast-player-bloc');
    $annotation.innerHTML = `
      <div class="podcast-player-list">
      </div>
      <div class="podcast-player-info">
        <h3 class="podcast-player-title"></h3>
        <p class="podcast-player-text"></p>
      </div>
    `;

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
      $items[i].addEventListener('click', function(event) {
        const $elt = event.target;
        let startAt = parseInt($elt.parentElement.getAttribute('data-annotation-start'));
        wavesurfer.play(startAt);
      });
    }

    wavesurfer.on('play', function() {
      $button.classList.remove('podcast-item-play');
      $button.classList.add('podcast-item-pause');
    });
    wavesurfer.on('pause', function() {
      $button.classList.add('podcast-item-play');
      $button.classList.remove('podcast-item-pause');
    });
  });

  // display info when ok
  wavesurfer.on('region-click', function (region, e) {
    e.stopPropagation();
    console.log('region clicked');
    // showNote(region);
    // Play on click, loop on shift click
    region.play();
  });

  wavesurfer.on('region-in', showNote);

  // function removeNote() {
  //   document.querySelector('#subtitle').textContent = '';
  // }

  function showNote (region) {
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
    console.log($player.querySelector('.podcast-player-list'));
    $player.querySelector('.podcast-player-list').scrollTop = $item.offsetTop - 90;

    console.log($item.offsetTop);
    console.log($item.offsetHeight);
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
  const $players = selectAll('.podcast-item');

  each($players, initPlayer);

  ////////////////////////////////////////
  // Wavesurfer                         //
  ////////////////////////////////////////

  const players = document.querySelectorAll('.podcast-player');

  for (let i = 0; i < players.length; i++) {
    initPlayer(players[i]);
  }
}

export default init;
