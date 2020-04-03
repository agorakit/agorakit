// Here we put custom compilers for unpoly
// Check docs here : https://unpoly.com/up.compiler

/*
To enable a wysiwyg editor, add a .wysiwyg class to a textarea

You can also provide a json url for atwho mention plugin
- data-mention-users for @users
- data-mention-discussions
- data-mention-files
*/
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



/*
Add a calendar to any div with the calendar class
- data-locale to define the correct locale
- data-json for the json url feed to use
*/
up.$compiler('.calendar', function($element, data) {

	var json = $element[0].getAttribute("data-json")
	var locale = $element[0].getAttribute("data-locale")
	$element.fullCalendar({
		lang: locale,
		events: json,
		header: {
			left: 'prev,next',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		eventClick:  function(event, jsEvent, view) {
			up.modal.visit(event.url, { target: '.content' });
			return false;
		},
		eventRender: function(event, element)
		{
			$(element).tooltip({title: event.group_name + ' : ' + event.title + ' : ' + event.summary});
		}
	});
});

/*
A simple spinner that is shown when a request takes too long
*/
up.compiler('.spinner', function(element) {
	function show() { element.style.display = 'block' }
	function hide() { element.style.display = 'none' }
	up.on('up:proxy:slow', show)
	up.on('up:proxy:recover', hide)
	hide()
});

/*
- add a tags class to select to enable selectize on it
- add data-allow-new-tags to allow the creation of new tags
*/
up.$compiler('select.tags', function($element, data) {
	var create = $element[0].hasAttribute("data-allow-new-tags")
	$element.selectize({
		persist: true,
		create: create
	});
});




/*
- scroll to first unread item
*/
up.compiler('#unread', function(element) {
	console.log(element);
	element.scrollIntoView({
		block: 'start',
		behavior: 'smooth',
		inline: 'nearest'
	});
});


/*
Datatables
*/
up.$compiler('.data-table', function($element) {
	$element.DataTable( {
		"pageLength": 25,
		dom: 'Bfrtip',
		buttons: [
			'csv', 'excel'
		]
	});
});

/* external links */
// Taken from : https://gist.github.com/CrocoDillon/7989214
// vanilla JavaScript

up.compiler('a', function(element) {
	if (element.hostname != window.location.hostname)
	{
		element.target = '_blank';
	}
});
