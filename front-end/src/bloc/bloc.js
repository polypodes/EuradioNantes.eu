////////////////////////////////////////
// Bloc js                            //
////////////////////////////////////////

////////////////////////////////////////
// Requirements                       //
////////////////////////////////////////
import {selectAll, each, on} from '../util/util';

////////////////////////////////////////
// Helpers & private methods          //
////////////////////////////////////////

/**
 * common helper function example
 * this is a trivial example
 */
function changeLocation($elt) {
  const $url = $elt
    .querySelector('[data-clickable-target="true"]');
  const path = $url.getAttribute('href');
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
  const $clickableElts = selectAll('[data-clickable="true"]');

  if ($clickableElts.lenght === 0) return;

  each($clickableElts, ($elt) => {
    $elt.addEventListener('click',
      changeLocation.bind(null, $elt),
    'true');
  });
}

export default init;
