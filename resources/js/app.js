// Bootstrap and Jquery
 try {
     window.$ = window.jQuery = require('jquery/dist/jquery');

     require('bootstrap');
 } catch (e) {}


// Open external links in blank tabs
require('./external_links');


// Trumbowyg wysiwyg editor
require('trumbowyg/dist/trumbowyg.min.js');
// ...with mention plugin
require('trumbowyg/dist/plugins/mention/trumbowyg.mention.min.js');

// Unpoly
require('unpoly/dist/unpoly.min.js');
require('unpoly/dist/unpoly-bootstrap3.min.js');

// Unpoly custom compilers (include after the rest)
require('./compilers');
