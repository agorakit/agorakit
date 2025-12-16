/**
* We use unpoly compilers for most of the javascript work.
* Check docs here : https://unpoly.com/up.compiler
*/

// Unpoly global config :

// set cache expiration to 5 seconds (instead of the default 15)
//up.network.config.cacheExpireAge = 5_000

// use unpoly on all links by default
up.link.config.followSelectors.push('a[href]')

// reveal content from the top of it
//up.viewport.config.revealTop = true

// bad response time is faster than default. Considered bad after 200 msecs
up.network.config.badResponseTime = 200

up.network.config.progressBar = true
up.fragment.config.runScripts = true
up.history.config.updateMetaTags = true

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

// Css loader taken from https://github.com/filamentgroup/loadCSS
/*! loadCSS. [c]2020 Filament Group, Inc. MIT License */
(function (w) {
	"use strict";
	/* exported loadCSS */
	var loadCSS = function (href, before, media, attributes) {
		// Arguments explained:
		// `href` [REQUIRED] is the URL for your CSS file.
		// `before` [OPTIONAL] is the element the script should use as a reference for injecting our stylesheet <link> before
		// By default, loadCSS attempts to inject the link after the last stylesheet or script in the DOM. However, you might desire a more specific location in your document.
		// `media` [OPTIONAL] is the media type or query of the stylesheet. By default it will be 'all'
		// `attributes` [OPTIONAL] is the Object of attribute name/attribute value pairs to set on the stylesheet's DOM Element.
		var doc = w.document;
		var ss = doc.createElement("link");
		var ref;
		if (before) {
			ref = before;
		}
		else {
			var refs = (doc.body || doc.getElementsByTagName("head")[0]).childNodes;
			ref = refs[refs.length - 1];
		}

		var sheets = doc.styleSheets;
		// Set any of the provided attributes to the stylesheet DOM Element.
		if (attributes) {
			for (var attributeName in attributes) {
				if (attributes.hasOwnProperty(attributeName)) {
					ss.setAttribute(attributeName, attributes[attributeName]);
				}
			}
		}
		ss.rel = "stylesheet";
		ss.href = href;
		// temporarily set media to something inapplicable to ensure it'll fetch without blocking render
		ss.media = "only x";

		// wait until body is defined before injecting link. This ensures a non-blocking load in IE11.
		function ready(cb) {
			if (doc.body) {
				return cb();
			}
			setTimeout(function () {
				ready(cb);
			});
		}
		// Inject link
		// Note: the ternary preserves the existing behavior of "before" argument, but we could choose to change the argument to "after" in a later release and standardize on ref.nextSibling for all refs
		// Note: `insertBefore` is used instead of `appendChild`, for safety re: http://www.paulirish.com/2011/surefire-dom-element-insertion/
		ready(function () {
			ref.parentNode.insertBefore(ss, (before ? ref : ref.nextSibling));
		});
		// A method (exposed on return object for external use) that mimics onload by polling document.styleSheets until it includes the new sheet.
		var onloadcssdefined = function (cb) {
			var resolvedHref = ss.href;
			var i = sheets.length;
			while (i--) {
				if (sheets[i].href === resolvedHref) {
					return cb();
				}
			}
			setTimeout(function () {
				onloadcssdefined(cb);
			});
		};

		function loadCB() {
			if (ss.addEventListener) {
				ss.removeEventListener("load", loadCB);
			}
			ss.media = media || "all";
		}

		// once loaded, set link's media back to `all` so that the stylesheet applies once it loads
		if (ss.addEventListener) {
			ss.addEventListener("load", loadCB);
		}
		ss.onloadcssdefined = onloadcssdefined;
		onloadcssdefined(loadCB);
		return ss;
	};
	// commonjs
	if (typeof exports !== "undefined") {
		exports.loadCSS = loadCSS;
	}
	else {
		w.loadCSS = loadCSS;
	}
}(typeof global !== "undefined" ? global : this));


function loadJquery() {
	return loadJS('https://code.jquery.com/jquery-3.7.0.min.js')
}




/***************  Unpoly Compilers ****************/



/*
To enable a wysiwyg editor, add a .wysiwyg class to a textarea

- data-mention-users-list should contain a json encoded list of users
- data-mention-discussions REDO TODO
- data-mention-files	REDO TODO
*/

up.compiler('.wysiwyg', async function (element, data) {
	await loadCSS('https://cdn.jsdelivr.net/npm/summernote@0.9.1/dist/summernote-lite.min.css')
	await loadJquery();
	await loadJS('https://cdn.jsdelivr.net/npm/summernote@0.9.1/dist/summernote-lite.min.js')


	// load mentions
	var mentions = JSON.parse(element.getAttribute("data-mention-users-list"))
	var group_id = element.getAttribute("data-group-id")

	var $summernote = $(element).summernote({

		minHeight: 200,
		maxHeight: 600,

		toolbar: [
			['style', ['style']],
			['font', ['bold', 'italic', 'strikethrough']],
			['list', ['ul', 'ol']],
			['insert', ['link', 'unlink', 'picture']],
			['view', ['codeview', 'help']]
		],

		styleTags: [
			'p',
			{ title: 'Blockquote', tag: 'blockquote', value: 'blockquote' },
			'pre', 'h2', 'h3', 'h4'
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
	await loadJS('https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.19/index.global.min.js')
	//await loadJS('https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.19/locales-all.global.min.js')

	var initialView = (localStorage.getItem("fcDefaultView") !== null ? localStorage.getItem("fcDefaultView") : "dayGridMonth");
	var json = element.getAttribute("data-json")
	var locale = element.getAttribute("data-locale")
	var create_url = element.getAttribute("data-create-url")
	var calendar = new FullCalendar.Calendar(element, {
		schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
		initialView: resourceTimeline,
		resources: [
			{ id: 'a', title: 'Auditorium A', occupancy: 40 },
			{ id: 'b', title: 'Auditorium B', occupancy: 60 },
			{ id: 'c', title: 'Auditorium C', occupancy: 40 },
			{ id: 'd', title: 'Auditorium D', occupancy: 40 },
			{ id: 'e', title: 'Auditorium E', occupancy: 60 },
			{ id: 'f', title: 'Auditorium F', occupancy: 60 },
			{ id: 'g', title: 'Auditorium G', occupancy: 60 },
			{ id: 'h', title: 'Auditorium H', occupancy: 40 },
			{ id: 'i', title: 'Auditorium I', occupancy: 70 },
			{ id: 'j', title: 'Auditorium J', occupancy: 70 },
			{ id: 'k', title: 'Auditorium K', occupancy: 70 },
			{ id: 'l', title: 'Auditorium L', occupancy: 75 },
			{ id: 'm', title: 'Auditorium M', occupancy: 40 },
			{ id: 'n', title: 'Auditorium N', occupancy: 40 },
			{ id: 'o', title: 'Auditorium O', occupancy: 40 },
			{ id: 'p', title: 'Auditorium P', occupancy: 40 },
			{ id: 'q', title: 'Auditorium Q', occupancy: 40 },
			{ id: 'r', title: 'Auditorium R', occupancy: 40 },
			{ id: 's', title: 'Auditorium S', occupancy: 40 },
			{ id: 't', title: 'Auditorium T', occupancy: 40 },
			{ id: 'u', title: 'Auditorium U', occupancy: 40 },
			{ id: 'v', title: 'Auditorium V', occupancy: 40 },
			{ id: 'w', title: 'Auditorium W', occupancy: 40 },
			{ id: 'x', title: 'Auditorium X', occupancy: 40 },
			{ id: 'y', title: 'Auditorium Y', occupancy: 40 },
			{ id: 'z', title: 'Auditorium Z', occupancy: 40 }
		],
		//locale: locale,
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
	var settings = {}
	if (data.tags) {
		var settings = { create: true }
	}
	new TomSelect(element, settings);
});

/*
- scroll to first unread item
*/
/*
up.compiler('#unread', function (element) {
	up.reveal(element, { behavior: "instant" })
	element.scrollIntoView();
});
*/



/*
Datatables using .data-table
*/
up.compiler('.data-table', async function (element) {

	await loadJquery();
	await loadJS('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js')
	await loadJS('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js')
	await loadJS('https://cdn.datatables.net/v/bs5/dt-1.13.6/b-2.4.2/b-html5-2.4.2/r-2.5.0/sr-1.3.0/datatables.min.js')
	await loadCSS('https://cdn.datatables.net/v/bs5/dt-1.13.6/b-2.4.2/b-html5-2.4.2/r-2.5.0/sr-1.3.0/datatables.min.css')

	$(element).DataTable({
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


// leaflet map
up.compiler('.js-map', async function (element, data) {
	await loadJquery();
	await loadJS('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js')
	await loadCSS('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css')

	$(document).ready(function () {
		// load all our points using geojson
		// taken from https://medium.com/@maptastik/loading-external-geojson-a-nother-way-to-do-it-with-jquery-c72ae3b41c01
		var points = $.ajax({
			url: data.jsonUrl,
			dataType: "json",
			success: console.log("points data successfully loaded."),
			error: function (xhr) {
				alert(xhr.statusText)
			}
		})


		// When loading is complete :
		$.when(points).done(function () {
			var map = L.map('mapid').setView([51.505, -0.09], 13);

			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
			}).addTo(map);





			// from https://stackoverflow.com/questions/23567203/leaflet-changing-marker-color

			const userIcon = L.divIcon({
				className: "my-custom-pin",
				iconAnchor: [0, 24],
				labelAnchor: [-6, 0],
				popupAnchor: [0, -36],
				html: `<span style="background-color: #1e60c9" class="marker"></span>`
			})

			const eventIcon = L.divIcon({
				className: "my-custom-pin",
				iconAnchor: [0, 24],
				labelAnchor: [-6, 0],
				popupAnchor: [0, -36],
				html: `<span style="background-color: #871ec9" class="marker"></span>`
			})

			const groupIcon = L.divIcon({
				className: "my-custom-pin",
				iconAnchor: [0, 24],
				labelAnchor: [-6, 0],
				popupAnchor: [0, -36],
				html: `<span style="background-color: #8dc91e" class="marker"></span>`
			})

			// from https://gist.github.com/geog4046instructor/80ee78db60862ede74eacba220809b64
			// replace Leaflet's default blue marker with a custom icon
			function createCustomIcon(feature, latlng) {
				if (feature.properties.type == 'user') {
					return L.marker(latlng, {
						icon: userIcon
					})
				}

				if (feature.properties.type == 'calendarevent') {
					return L.marker(latlng, {
						icon: eventIcon
					})
				}

				if (feature.properties.type == 'group') {
					return L.marker(latlng, {
						icon: groupIcon
					})
				}

				return L.marker(latlng, {
					icon: userIcon
				})
			}

			// Add requested external GeoJSON to map
			var allPoints = L.geoJSON(points.responseJSON, {
				pointToLayer: createCustomIcon
			})
				.bindPopup(function (layer) {
					return "<strong>" + layer.feature.properties.title + '</strong><br/>' + layer
						.feature
						.properties.description;
				}).addTo(map);



			map.fitBounds(allPoints.getBounds());

		});

	});
})
