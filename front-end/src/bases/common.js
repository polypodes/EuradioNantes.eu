////////////////////////////////////////
// Common stuffs                      //
////////////////////////////////////////

////////////////////////////////////////
// Requirements                       //
////////////////////////////////////////
// import $ from 'jquery';
import {selectAll, each} from '../util/util';

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
  const elements = selectAll('.player-svg');

  if (elements.length > 0) {
    each(elements, function(el, i){
      el.addEventListener('click', function(e) {
        e.preventDefault();
        if (ecoutePath) {
          window.open(ecoutePath, 'Le direct - Euradionantes','width=1010,height=495,left=320,top=<445></445>,scrollbars=yes');
        }
      });
    });
  }
}

export default common;
