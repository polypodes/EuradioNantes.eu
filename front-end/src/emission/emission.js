////////////////////////////////////////
// Podcast js                         //
////////////////////////////////////////

////////////////////////////////////////
// Requirements                       //
////////////////////////////////////////
import {select, each, on} from '../util/util';

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
    const $dirty = select("#emission-quickmenu");
    if ($dirty) {
        $dirty.addEventListener('change', function() {
            window.location.pathname = this.options[this.selectedIndex].getAttribute('data-href');
        });
    }
}

export default navSelect;
