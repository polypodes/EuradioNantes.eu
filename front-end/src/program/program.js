////////////////////////////////////////
// Program js                         //
////////////////////////////////////////

////////////////////////////////////////
// Requirements                       //
////////////////////////////////////////
import {selectAll, each, on, addClass, removeClass, hasClass} from '../util/util';

////////////////////////////////////////////////////////////////////////////////
// Helpers & private methods          //
////////////////////////////////////////

function updatePanels(panelId) {
  var panels = selectAll('.program-tab');

  each(panels, function(el, i){
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
  if (selectAll('.program-tab').length === 0) return;

  const elements = selectAll('.actu-list-filter'),
    className = 'actu-list-filter-selected'
  ;
  var active = selectAll('.actu-list-filter-selected')[0];

  each(elements, function(el, i){
    var links = el.querySelectorAll('a');
    var contentId = links[0].getAttribute('href').substr(1);
    var contentElem = document.getElementById(contentId);

    // if elem has className, show panel, else hide it
    if (contentElem) {
      if (hasClass(el, className)) {
        contentElem.style.display = '';
      } else {
        contentElem.style.display = 'none';
      }
    }

    links[0].addEventListener('click', function(e) {
      var contentId = this.getAttribute('href').substr(1);

      // remove className
      //if (active.classList) { active.classList.remove(className); }
      //else { active.className = active.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' '); }
      removeClass(active, className);

      // addActive
      active = e.target.parentNode;
      addClass(active, className);

      updatePanels(contentId);
      window.location.hash = '#' + contentId;
      e.preventDefault();
    })
  });


  // check if tab is selected in url hash
  if (location.hash.match(/^#tab[0-9]/)) {
    //removeClass(active, className);
    var link = document.querySelectorAll('a[href="'+location.hash+'"]')[0];
    link.click();
    //active = elem.parentNode;
  } else  {
    var num = dayNumber || 0;
    var link = document.querySelectorAll('.actu-list-filter')[num].querySelectorAll('a')[0];
    link.click();
  }

}

export default init;
