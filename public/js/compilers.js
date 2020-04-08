// Here we put custom compilers for unpoly
// Check docs here : https://unpoly.com/up.compiler


/*
To enable a wysiwyg editor, add a .wysiwyg class to a textarea

You can also provide a json url for ckeditor mention plugin
- data-mention-users for @users
- data-mention-discussions REDO TODO
- data-mention-files	REDO TODO
*/

up.compiler('.wysiwyg', function(element, data) {

	// first le'ts handle the mentions
	mentions_loaded = false
	mentions = []

	// if mentions are already loaded we directly filter and return
	if (mentions_loaded) {
		resolve(mentions.filter(isItemMatching).slice( 0, 10 ))
	}

	// this function will return the matching mentions based on the queryText entered by the user after an @
	function getFeedItems(queryText) {
		return new Promise( (resolve, reject) => {

			// else we load the json and return
			var mention_users = element.getAttribute("data-mention-users")
			$.getJSON(mention_users, function(mentions) {
				mentions_loaded = true;
				console.log(mentions)
				resolve(mentions.filter(isItemMatching).slice( 0, 10 ))
			})
			.fail(function() {
				console.log( "error loading mentions" );
				reject();
			})

		})

		// This function filters items based on the name property (it contains both username and real name)
		function isItemMatching( item ) {
			// Make the search case-insensitive.
			const searchString = queryText.toLowerCase();

			// Include an item in the search results if the name or username includes the current user input.
			return (
				item.name.toLowerCase().includes( searchString ) ||
				item.id.toLowerCase().includes( searchString )
			);
		}
	}

	function customItemRenderer( item ) {
		const itemElement = document.createElement( 'span' );

		itemElement.classList.add( 'custom-item' );
		itemElement.id = `mention-list-item-id-${ item.userId }`;
		itemElement.textContent = `${ item.name } `;

		const usernameElement = document.createElement( 'span' );

		usernameElement.classList.add( 'custom-item-username' );
		usernameElement.textContent = item.id;

		itemElement.appendChild( usernameElement );


		return itemElement;
	}


	// This instantiate the CKeditor
	ClassicEditor
	.create( element, {
		mention: {
			feeds: [
				{
					marker: '@',
					feed: getFeedItems,
					minimumCharacters: 0,
					itemRenderer: customItemRenderer
				}
			]
		},

		toolbar: {
			items: [
				'heading',
				'|',
				'bold',
				'italic',
				'link',
				'bulletedList',
				'numberedList',
				'|',
				'indent',
				'outdent',
				'|',
				'blockQuote',
				'insertTable',
				'mediaEmbed',
				'undo',
				'redo',
			]
		},
		language: 'en',
		image: {
			toolbar: [
				'imageTextAlternative',
				'imageStyle:full',
				'imageStyle:side'
			]
		},
		table: {
			contentToolbar: [
				'tableColumn',
				'tableRow',
				'mergeTableCells'
			]
		},


	} )
	.then( editor => {
		window.editor = editor;

	} )
	.catch( error => {
		console.error( 'Oops, something gone wrong!' );
		console.error( error );
	} );

});



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
