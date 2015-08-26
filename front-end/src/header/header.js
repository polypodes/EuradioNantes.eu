////////////////////////////////////////
// Header                             //
////////////////////////////////////////

////////////////////////////////////////
// Requirements                       //
////////////////////////////////////////
import {
  cl,
  documentReady,
  each,
  on,
  select,
  selectAll
} from '../util/util';

////////////////////////////////////////
// Helpers                            //
////////////////////////////////////////

////////////////////////////////////////
// private properties                 //
////////////////////////////////////////
const $menuBtn          = select('.header-menu-button');
const $menu             = select('.header-menu');
const $menuItems        = select('.header-items');
const $linkWithChildren = selectAll('.header-link-has-children');
const $goBackBtn        = select('.header-menu-back');
const $level2           = selectAll('.header-menu ul > li > ul');

////////////////////////////////////////
// private methods                    //
////////////////////////////////////////

/**
 * triggered when document is ready
 * @return {void}
 */
function documentLoaded() {

  on($menuBtn, toggleMenu, 'click', 'touchstart');
  on($goBackBtn, hideLevel2, 'click', 'touchstart');

  each($linkWithChildren, ($elt) => {
    on($elt, showLevel2, 'click', 'touchstart');
  });
}

/**
 * show/hide menu mobile
 * @return {void}
 */
function toggleMenu() {
  if (select('.header-menu-show') !== null) {
    $menu.classList.add('header-menu-hide');
    $menu.classList.remove('header-menu-show');
    select('body').classList.remove('header-menu-opened');
  } else {
    $menu.classList.remove('header-menu-hide');
    $menu.classList.add('header-menu-show');
    select('body').classList.add('header-menu-opened');
  }

  hideLevel2();
}

/**
 * show second level of menu mobile
 * @param  {event} event from addEventListener
 * @return {void}
 */
function showLevel2(event) {
  event.preventDefault();

  const $li = event.target.parentNode;
  const $ul = $li.querySelector('ul');

  $ul.classList.add('header-level2-show');
  $menuItems.classList.add('header-level2');

  // show go back button
  $goBackBtn.classList.add('header-menu-back-show');
}

/**
 * hide second level of menu mobile
 * @return {void}
 */
function hideLevel2() {
  each($level2, ($elt) => {
    $elt.classList.remove('header-level2-show');
  });
  $menuItems.classList.remove('header-level2');

  // hide go back button
  $goBackBtn.classList.remove('header-menu-back-show');
}

////////////////////////////////////////
// Public API                         //
////////////////////////////////////////

/**
 * init function
 * this is a trivial init function
 */
function init() {
  documentReady(documentLoaded);
}

export default init;
