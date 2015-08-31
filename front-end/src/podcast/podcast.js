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
}

export default init;
