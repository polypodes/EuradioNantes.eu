// modules
import common from './bases/common.js';
import header from './header/header.js';
import bloc from './bloc/bloc.js';
import podcast from './podcast/podcast.js';
import emission from './emission/emission.js';

////////////////////////////////////////
// Dependencies init                  //
////////////////////////////////////////

[
  common,
  header,
  bloc,
  podcast,
  emission
  // append here your dependencies

].map(dependency => dependency());

////////////////////////////////////////
// below - shit to remove in prod     //
////////////////////////////////////////
