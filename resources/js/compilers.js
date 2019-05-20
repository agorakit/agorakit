// Here we put custom compilers for unpoly
// Check docs here : https://unpoly.com/up.compiler


up.$compiler('textarea.wysiwyg', function($element) {
  $element.trumbowyg({
    btns: [
      /*['mention'],*/
      ['viewHTML'],
      ['undo', 'redo'], // Only supported in Blink browsers
      ['formatting'],
      ['strong', 'em'],
      ['link'],
      ['insertImage'],
      ['unorderedList', 'orderedList'],
      ['removeformat'],
      ['fullscreen']
    ],
    minimalLinks: true,
    svgPath : '/svg/icons.svg',
  /*
    plugins: {
        mention: {
            source: [
                {login: 'lucy'},
                {login: 'jdoe'},
                {login: 'mlisa'},
                {login: 'jcesar'},
            ]
        }
    }
    */
  })
})
