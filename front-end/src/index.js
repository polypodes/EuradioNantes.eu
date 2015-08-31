// modules
import common from './bases/common.js';
import header from './header/header.js';
import bloc from './bloc/bloc.js';
import podcast from './podcast/podcast.js';

////////////////////////////////////////
// Dependencies init                  //
////////////////////////////////////////

[
  common,
  header,
  bloc,
  podcast
  // append here your dependencies

].map(dependency => dependency());

////////////////////////////////////////
// below - shit to remove in prod     //
////////////////////////////////////////
