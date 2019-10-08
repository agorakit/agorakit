// Here we put custom compilers for unpoly
// Check docs here : https://unpoly.com/up.compiler




up.$compiler('.wysiwyg', function($element, data) {

  $element.trumbowyg({
    btns: [
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
  })


  var mention_users = $element[0].getAttribute("data-mention-users")
  if (mention_users)
  {
    console.log(mention_users)
    $('.trumbowyg-editor').atwho({
      at: "@",
      data: mention_users,
      insertTpl: "${atwho-at}${username}"
    });
  }

  var mention_files = $element[0].getAttribute("data-mention-files")
  if (mention_files)
  {
    console.log(mention_files)
    $('.trumbowyg-editor').atwho({
      at: "f:",
      data: mention_files,
      insertTpl: "f:${id}"
    });
  }


  var mention_discussions = $element[0].getAttribute("data-mention-discussions")
  if (mention_discussions)
  {
    console.log(mention_discussions)
    $('.trumbowyg-editor').atwho({
      at: "d:",
      data: mention_discussions,
      insertTpl: "d:${id}"
    });
  }

  $('body').on('mouseup', '.atwho-view-ul li', function (e) {
    e.stopPropagation();
  });

})
