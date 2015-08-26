// modules
import common from './bases/common.js';
import header from './header/header.js';

////////////////////////////////////////
// Dependencies init                  //
////////////////////////////////////////

[
  common,
  header
  // append here your dependencies

].map(dependency => dependency());

////////////////////////////////////////
// below - shit to remove in prod     //
////////////////////////////////////////
