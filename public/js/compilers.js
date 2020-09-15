// Here we put custom compilers for unpoly
// Check docs here : https://unpoly.com/up.compiler


/*
To enable a wysiwyg editor, add a .wysiwyg class to a textarea

You can also provide a json url for ckeditor mention plugin
- data-mention-users for @users
- data-mention-discussions REDO TODO
- data-mention-files	REDO TODO
*/

up.compiler('.wysiwyg', function (element, data) {

	// first le'ts handle the mentions
	mentions_loaded = false
	mentions = []

	// if mentions are already loaded we directly filter and return
	if (mentions_loaded) {
		resolve(mentions.filter(isItemMatching).slice(0, 10))
	}

	// this function will return the matching mentions based on the queryText entered by the user after an @
	function getFeedItems(queryText) {
		return new Promise((resolve, reject) => {

			// else we load the json and return
			var mention_users = element.getAttribute("data-mention-users")
			$.getJSON(mention_users, function (mentions) {
				mentions_loaded = true;
				console.log(mentions)
				resolve(mentions.filter(isItemMatching).slice(0, 10))
			})
				.fail(function () {
					console.log("error loading mentions");
					reject();
				})

		})

		// This function filters items based on the name property (it contains both username and real name)
		function isItemMatching(item) {
			// Make the search case-insensitive.
			const searchString = queryText.toLowerCase();

			// Include an item in the search results if the name or username includes the current user input.
			return (
				item.name.toLowerCase().includes(searchString) ||
				item.id.toLowerCase().includes(searchString)
			);
		}
	}

	function customItemRenderer(item) {
		const itemElement = document.createElement('span');

		itemElement.classList.add('custom-item');
		itemElement.id = `mention-list-item-id-${item.userId}`;
		itemElement.textContent = `${item.name} `;

		const usernameElement = document.createElement('span');

		usernameElement.classList.add('custom-item-username');
		usernameElement.textContent = item.id;

		itemElement.appendChild(usernameElement);


		return itemElement;
	}


	// This instantiate the CKeditor
	ClassicEditor
		.create(element, {
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
			mediaEmbed: {
				previewsInData: true
			},
			removePlugins: ['MediaEmbed'],

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
					'imageUpload',
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
				],
				upload: {
					panel: {
						items: ['insertImageViaUrl']
					}
				}
			},
			table: {
				contentToolbar: [
					'tableColumn',
					'tableRow',
					'mergeTableCells'
				]
			},


		})
		.then(editor => {
			window.editor = editor;

		})
		.catch(error => {
			console.error('Oops, something gone wrong!');
			console.error(error);
		});

});



/*
Add a calendar to any div with the calendar class
- data-locale to define the correct locale
- data-json for the json url feed to use
*/

up.compiler('.calendar', function (element, data) {


	var defaultView = (localStorage.getItem("fcDefaultView") !== null ? localStorage.getItem("fcDefaultView") : "dayGridMonth");
	var json = element.getAttribute("data-json")
	var locale = element.getAttribute("data-locale")
	var create_url = element.getAttribute("data-create-url")
	var calendar = new FullCalendar.Calendar(element, {
		defaultView: defaultView,
		plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
		locale: locale,
		events: json,
		selectable: true,
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'timeGridDay,timeGridWeek,dayGridMonth,listMonth'
		},

		// show add event form modal on date click / select
		select: function (info) {
			if (create_url) {
				// correct timezone https://stackoverflow.com/questions/10830357/javascript-toisostring-ignores-timezone-offset
				var start = new Date(info.start.getTime() - (info.start.getTimezoneOffset() * 60000)).toISOString();
				var stop = new Date(info.end.getTime() - (info.end.getTimezoneOffset() * 60000)).toISOString();
				url = create_url + '?start=' + start + '&stop=' + stop
				up.modal.visit(url, { target: '.tab_content' });
			}
		},

		// show event detail modal on event click
		eventClick: function (info) {
			info.jsEvent.preventDefault(); // don't let the browser navigate
			up.modal.visit(info.event.url, { target: '.content' });
		},

		// add tooltip to all events
		eventRender: function (info) {
			content = '<strong>' + info.event.extendedProps.group_name + '</strong><br/>' + info.event.extendedProps.summary;
			$(info.el).tooltip({ title: content, html: true });
		},

		// store the current view type on each view change
		viewSkeletonRender: function (info) {
			localStorage.setItem("fcDefaultView", info.view.type);
		}

	});
	calendar.render();
});


/*
A simple spinner that is shown when a request takes too long
*/
up.compiler('.spinner', function (element) {
	function show() { element.style.display = 'block' }
	function hide() { element.style.display = 'none' }
	up.on('up:proxy:slow', show)
	up.on('up:proxy:recover', hide)
	hide()
});

//up.proxy.config.slowDelay=300


/*
Warn user that there is no network 
*/
/*
up.on('up:proxy:fatal', function(){
	up.element.show(up.element.get('.network-error'))
})
*/


up.compiler('.network-error', function (element) {
	function show() { element.style.display = 'block' }
	function hide() { element.style.display = 'none' }
	up.on('up:proxy:fatal', show)
	up.on('up:proxy:recover', hide)
	hide()
});



/*
- add a tags class to select to enable selectize on it
- add data-allow-new-tags to allow the creation of new tags
*/
up.$compiler('select.tags', function ($element, data) {
	var create = $element[0].hasAttribute("data-allow-new-tags")
	$element.selectize({
		persist: true,
		create: create
	});
});




/*
- scroll to first unread item
*/


up.compiler('#unread', function (element) {
	console.log(element);
	element.scrollIntoView({
		block: 'start',
		behavior: 'instant',
		inline: 'nearest'
	});
});



/*
Datatables
*/
up.$compiler('.data-table', function ($element) {
	$element.DataTable({
		"pageLength": 10,
		stateSave: true,
		dom: 'frtpBi',
		buttons: [
			{
				extend: 'excel',
				text: 'Export excel',
				className: 'btn btn-secondary'
			},
			{
				extend: 'csv',
				text: 'Export csv',
				className: 'btn btn-secondary'
			}
		]
	});
});

/* external links */
// Taken from : https://gist.github.com/CrocoDillon/7989214
// vanilla JavaScript

up.compiler('a', function (element) {
	if (element.hostname != window.location.hostname) {
		element.target = '_blank';
	}
});


/*
Reloads every xx seconds using poll automagically
*/
up.$compiler('.poll', function ($element, data) {
	console.log(data)
	var interval = parseInt($element.attr('poll') || 10000);
	var timer = setInterval(function () {
		if (!document.hidden) {
			up.replace("#live-content", data.url, {
				history: false,
				cache: false
			})
		}
	}, interval);
	return function () { clearInterval(timer) } // stop polling when element is removed
});
