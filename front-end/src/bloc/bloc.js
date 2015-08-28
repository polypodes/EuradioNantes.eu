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
function init() {
  const $clickableElts = selectAll('[data-clickable="true"]');

  each($clickableElts, ($elt) => {
    $elt.addEventListener('click', () => {
      const $url = $elt.querySelector('[data-clickable-target]');
      const path = $url.getAttribute('href');
      const host = location.host;
      location = `${host}/${path}`;
    }, 'true');
  });
}

export default init;
