////////////////////////////////////////
// Share js                         //
////////////////////////////////////////

////////////////////////////////////////
// Requirements                       //
////////////////////////////////////////
import {selectAll, each} from '../util/util';

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
  const shareLinks = selectAll('.share-link'),
        embed = document.getElementById('embed-code')
  ;

  if (shareLinks.length === 0) return;

  each(shareLinks, function(el, i){
    el.addEventListener('click', function(e) {
      e.preventDefault();
      window.open(
        this.getAttribute('href'),
        this.getAttribute('title'),
        'width=495,height=495,left=320,top=445'
      );
    });
  });

  if (embed) {
    // show / hide embed code
    embed.style.display = 'none';
    document.getElementById('share-embeded').addEventListener('click', function(e) {
      e.preventDefault();

      if (embed.style.display == 'none') {
        embed.style.display = '';
      } else {
        embed.style.display = 'none';
      }
    });
  }
}

export default init;
