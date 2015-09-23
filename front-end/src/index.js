// modules
import common from './bases/common.js';
import header from './header/header.js';
import bloc from './bloc/bloc.js';
import podcast from './podcast/podcast.js';
import emission from './emission/emission.js';
import program from './program/program.js';
import ecoute from './program/ecoute.js';
import share from './share/share.js';

////////////////////////////////////////
// Dependencies init                  //
////////////////////////////////////////

[
  common,
  header,
  bloc,
  podcast,
  emission,
  program,
  ecoute,
  share
  // append here your dependencies

].map(dependency => dependency());

////////////////////////////////////////
// below - shit to remove in prod     //
////////////////////////////////////////
