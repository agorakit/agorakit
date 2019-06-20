// Bootstrap and Jquery
try {
  window.$ = window.jQuery = require('jquery/dist/jquery');
  require('bootstrap');

  /**
   * Add a scroll to top functionnalities
   *
   * @type {{default?}|{init: scrolltotop.init, controlHTML: string, controlattrs: {offsetx: number, offsety: number}, togglecontrol: scrolltotop.togglecontrol, state: {isvisible: boolean, shouldvisible: boolean}, keepfixed: scrolltotop.keepfixed, anchorkeyword: string, setting: {scrollduration: number, fadeduration: number[], startline: number, scrollto: number}, scrollup: scrolltotop.scrollup}}
   */
  let scrollToTop = require('./components/scrollToTop');
  scrollToTop.init();

} catch (e) {

}


// Open external links in blank tabs
require('./external_links');


// Trumbowyg wysiwyg editor
require('trumbowyg/dist/trumbowyg.min.js');
require('./components/trumbowyg.pasteembed');
// ...with mention plugin
require('trumbowyg/dist/plugins/mention/trumbowyg.mention.min.js');

// Unpoly
require('unpoly/dist/unpoly.min.js');
//require('unpoly/dist/unpoly-bootstrap3.min.js');

// tribute
//import Tribute from "tributejs";
//window.Tribute = Tribute;


// Unpoly custom compilers (include after the rest)
//require('./compilers');
