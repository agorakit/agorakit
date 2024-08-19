/**
 * Unpoly global config 
 */

// set cache expiration to 5 seconds (instead of the default 15)
up.network.config.cacheExpireAge = 5_000

// use unpoly on all links by default
up.link.config.followSelectors.push('a[href]')

// reveal content from the top of it
//up.viewport.config.revealTop = true

// bad response time is faster than default. Considered bad after 200 msecs
up.network.config.badResponseTime = 200


up.network.config.progressBar = true
up.fragment.config.runScripts = true




// JS script loader, taken from : https://makandracards.com/makandra/52361-unpoly-loading-large-libraries-on-demand
let jsLoaded = {};

function loadJS(url) {

	function createScriptTag(url) {
		let scriptTag = document.createElement("script")
		scriptTag.src = url
		return scriptTag
	}

	let cachedPromise = jsLoaded[url]
	if (cachedPromise) {
		return cachedPromise
	} else {
		let promise = new Promise((resolve, reject) => {
			let scriptTag = createScriptTag(url)
			scriptTag.addEventListener('load', resolve)
			scriptTag.addEventListener('error', reject)
			document.body.appendChild(scriptTag)
		})
		jsLoaded[url] = promise
		return promise
	}
}

let cssLoaded = {};
function loadCSS(url) {

	function createScriptTag(url) {
		let linkTag = document.createElement("link")
		linkTag.setAttribute("rel", "stylesheet")
		linkTag.setAttribute("type", "text/css")
		linkTag.href = url
		return linkTag
	}

	let cachedPromise = cssLoaded[url]
	if (cachedPromise) {
		return cachedPromise
	} else {
		let promise = new Promise((resolve, reject) => {
			let linkTag = createScriptTag(url)
			linkTag.addEventListener('load', resolve)
			linkTag.addEventListener('error', reject)
			document.body.appendChild(linkTag)
		})
		cssLoaded[url] = promise
		return promise
	}
}

function loadJquery() {
	return loadJS('https://code.jquery.com/jquery-3.7.0.min.js')
}




/**
 * Unpoly compilers
 * Here we put custom compilers for unpoly
 * Check docs here : https://unpoly.com/up.compiler
 * 
 * Basically, just add a specific class to an element to add behavior.
 * 
 * Supported classes are defined below :
 */



/*
To enable a wysiwyg editor, add a .wysiwyg class to a textarea
	
- data-mention-users-list should contain a json encoded list of users
- data-mention-discussions REDO TODO
- data-mention-files	REDO TODO
*/

up.compiler('.wysiwyg', async function (element, data) {
	await loadJquery();
	await loadJS('https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js')
	await loadCSS('https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css')


	// load mentions
	var mentions = JSON.parse(element.getAttribute("data-mention-users-list"))
	var group_id = element.getAttribute("data-group-id")

	var $summernote = $(element).summernote({

		minHeight: 200,
		maxHeight: 600,

		toolbar: [
			['style', ['style']],
			['font', ['bold', 'clear']],
			['color', ['color']],
			['para', ['ul', 'ol']],
			['insert', ['link', 'picture']],
			['view', ['codeview', 'help']]
		],

		// this is the main call back to upload a file (image or anything else with summernote)
		// multiple files can be uploaded at once
		callbacks: {
			onImageUpload: function (files) {
				for (var i = 0; i < files.length; i++) {
					sendFile($summernote, files[i], group_id);
				}
			}
		},

		hint: {
			mentions: mentions,
			match: /\B@(\w*)$/,
			search: function (keyword, callback) {
				callback($.grep(this.mentions, function (item) {
					return item.indexOf(keyword) == 0;
				}));
			},
			content: function (item) {
				setTimeout(() => { $summernote.summernote('insertText', ''); }, 100);
				return '@' + item;
			}
		}
	});


});

/*
// fix summernote dropdown for bootstrap
up.compiler('.note-toolbar', function (element) {
	var noteBar = $(element)
	noteBar.find('[data-toggle]').each(function () {
		$(this).attr('data-bs-toggle', $(this).attr('data-toggle')).removeAttr('data-toggle');
	});
});
*/


/**
 * This function upload a file to the server and in return it will get a file id, to add f:xxx to the thextarea 
 * to be later rendered as a nice embeded file.
 */
function sendFile($summernote, file, group_id) {
	var formData = new FormData();
	formData.append("file", file);
	formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
	//group_id = $('meta[name="group-id"]').attr('content');

	$.ajax({
		url: '/groups/' + group_id + '/files/create',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		type: 'POST',
		success: function (data) {
			$summernote.summernote('pasteHTML', ' &nbsp; f:' + data + ' &nbsp; ')
			$('.note-status-output').html(
				'<div class="alert alert-info">' +
				'Upload OK' +
				'</div>'
			);
		},
		error: function (data) {
			$('.note-status-output').html(
				'<div class="alert alert-danger">' +
				'File upload failled' +
				'</div>'
			);
		}
	});
}



/*
Add a calendar to any div with the .js-calendar class
- data-locale to define the correct locale
- data-json for the json url feed to use
*/

up.compiler('.js-calendar', async function (element, data) {

	await loadJquery();
	await loadJS('https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js')
	await loadJS('https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.9/locales-all.global.min.js')

	var initialView = (localStorage.getItem("fcDefaultView") !== null ? localStorage.getItem("fcDefaultView") : "dayGridMonth");
	var json = element.getAttribute("data-json")
	var locale = element.getAttribute("data-locale")
	var create_url = element.getAttribute("data-create-url")
	var calendar = new FullCalendar.Calendar(element, {
		initialView: initialView,
		locale: locale,
		events: json,
		selectable: true,
		headerToolbar: {
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
				up.navigate({ url: url, layer: 'new' });
			}
		},

		// show event detail modal on event click
		eventClick: function (info) {
			info.jsEvent.preventDefault(); // don't let the browser navigate
			up.navigate({ url: info.event.url, layer: 'new' });
		},


		// add tooltip to all events
		/*
		eventDidMount: function (info) {
			content = info.event.extendedProps.tooltip;
			$(info.el).tooltip({ title: content, html: true });
		},
		*/

		// store the current view type on each view change
		viewDidMount: function (info) {
			console.log(info.view.type);
			localStorage.setItem("fcDefaultView", info.view.type);
		}

	});
	calendar.render();
});





/*
- add a tags class to select to enable selectize on it
- add data-tags to allow the creation of new tags
*/
up.compiler('.js-tags', async function (element, data) {
	await loadCSS('https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css')
	await loadJS('https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js')
	console.log()
	var settings = {}
	if (data.tags) {
		var settings = { create: true }
	}
	new TomSelect(element, settings);
});

/*
- scroll to first unread item
*/
up.compiler('#unread', function (element) {
	up.reveal(element, { behavior: "instant" })
});



/*
Datatables using .data-table 
*/
up.$compiler('.data-table', async function ($element) {

	await loadJquery();
	await loadJS('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js')
	await loadJS('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js')
	await loadJS('https://cdn.datatables.net/v/bs5/dt-1.13.6/b-2.4.2/b-html5-2.4.2/r-2.5.0/sr-1.3.0/datatables.min.js')
	await loadCSS('https://cdn.datatables.net/v/bs5/dt-1.13.6/b-2.4.2/b-html5-2.4.2/r-2.5.0/sr-1.3.0/datatables.min.css')

	$element.DataTable({
		"pageLength": 10,
		stateSave: true,
		responsive: true,
		dom: 'frtpBi',
		buttons: [
			{
				extend: 'excel',
				text: 'Export excel',
				className: 'btn btn-primary bg-secondary'
			},
			{
				extend: 'csv',
				text: 'Export csv',
				className: 'btn btn-primary bg-secondary'
			}
		]
	});
});

/* Open external links in new window */
// Taken from : https://gist.github.com/CrocoDillon/7989214
// vanilla JavaScript

up.compiler('a', function (element) {
	if (element.hostname != window.location.hostname) {
		element.target = '_blank';
	}
});



/**
 * Add simple history.back behaviour onclick on element 
 */
up.compiler('.js-back', function (element) {

	element.onclick = function () {
		window.history.back();
		return false;
	};

});

// network errors show message
up.compiler('.js-network-error', function (element) {
	function show() { element.style.display = 'block' }
	function hide() { element.style.display = 'none' }
	up.on('up:request:offline', show)
	up.on('up:network:recover', hide)
	hide()
});


// loading bar
up.compiler('.js-loader', function (element) {
	function show() { element.style.display = 'block' }
	function hide() { element.style.display = 'none' }
	up.on('up:network:late', show)
	// could also be  up.on('up:network:loading', show),
	up.on('up:request:loaded', hide)
	// could also be  up.on('up:network:recover', hide)
	hide()
});