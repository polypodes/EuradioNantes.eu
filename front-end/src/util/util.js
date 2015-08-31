////////////////////////////////////////
// Util : set of usefull function     //
////////////////////////////////////////

////////////////////////////////////////
// Requirements                       //
////////////////////////////////////////
// import $ from 'jquery';

////////////////////////////////////////
// Helpers                            //
////////////////////////////////////////

////////////////////////////////////////
// private properties                 //
////////////////////////////////////////

////////////////////////////////////////
// private methods                    //
////////////////////////////////////////

////////////////////////////////////////
// Public API                         //
////////////////////////////////////////

/**
 * select the first matched element
 * @param  {string} selector: css selector
 * @return {elementHTML} html node
 */
function select(selector) {
  return document.querySelector(selector);
}

/**
 * select all matched element
 * @param  {string} selector: css selector
 * @return {array[elementHTML]} an array of html node
 */
function selectAll(selector) {
  return document.querySelectorAll(selector);
}

function cl(msg) {
  console.log(msg);
}

function each(array, callback) {
  for (let i = 0; i < array.length; i++) {
    callback(array[i]);
  }
}

/**
 * find a node in HTMLCollection of node
 * @param  {HTMLCollection} HTMLCollection
 * @param  {string} className: ex: Lorem-ipsum (without dot)
 * @return {HTMLnode || false} If node found it return
 * the node else it return false
 */
function findNode(HTMLCollection, className) {
  for (let i = HTMLCollection.length - 1; i >= 0; i--) {
    if (HTMLCollection[i].classList.contains(className)) {
      return HTMLCollection[i];
    }
  }

  return false;
};

/**
 * equivalent of jquery 'ready'
 * @param  {Function} callback
 * @return {void}
 */
function documentReady(callback) {
  if (document.readyState !== 'loading') {
    callback();
  } else {
    document.addEventListener('DOMContentLoaded', callback);
  }
}

/**
 * on: jquery equivalent of .on({event}, {fn})
 * @param  {elementHTML}    elt: an HTML node
 * @param  {Function}  callback
 * @param  {...[string]} events: an event or
 * a list of event (ex: click, touchstart)
 * @return {void}
 */
function on(elt, callback, ...events) {
  /*eslint-disable */
  events.map((event) => elt.addEventListener(event, callback.bind(this, elt)));
  /*eslint-enable */
}

export default {
  cl,
  documentReady,
  each,
  on,
  select,
  selectAll,
  findNode
};
