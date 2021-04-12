// Here we put custom compilers for unpoly
// Check docs here : https://unpoly.com/up.compiler


/*
To enable a wysiwyg editor, add a .wysiwyg class to a textarea

You can also provide a json url for ckeditor mention plugin
- data-mention-users-list should contain a json encoded list of users
- data-mention-discussions REDO TODO
- data-mention-files	REDO TODO
*/

up.compiler('.wysiwyg', function (element, data) {

	// load mentions
	var mentions = JSON.parse(element.getAttribute("data-mention-users-list"))

	console.log(mentions);


	var $summernote = $(element).summernote({

		toolbar: [
			['style', ['style']],
			['font', ['bold', 'underline', 'clear']],
			/*['fontname', ['fontname']],*/
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['table', ['table']],
			['insert', ['link', 'picture', 'video']],
			['view', ['fullscreen', 'codeview', 'help']],
		],

		// this is the main call back to upload a file (image or anything else with summernote)
		callbacks: {
			onImageUpload: function (files) {
				console.log(files);
				for (var i = 0; i < files.length; i++) {
					console.log(files[i])
					sendFile($summernote, files[i]);
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
				return '@' + item;
			}
		}
	});



});


function sendFile($summernote, file) {
	var formData = new FormData();
	formData.append("file", file);
	formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
	group_id = $('meta[name="group-id"]').attr('content');


	$.ajax({
		url: '/groups/' + group_id + '/files/create',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		type: 'POST',
		success: function (data) {
			console.log(data);
			$summernote.summernote('insertImage', data, function ($image) {
				$image.attr('src', data);
			});
		}
	});
}



/*
Add a calendar to any div with the calendar class
- data-locale to define the correct locale
- data-json for the json url feed to use
*/

up.compiler('.calendar', function (element, data) {


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
				up.modal.visit(url, { target: '.tab_content' });
			}
		},

		// show event detail modal on event click
		eventClick: function (info) {
			info.jsEvent.preventDefault(); // don't let the browser navigate
			up.modal.visit(info.event.url, { target: '.content' });
		},

		// add tooltip to all events
		eventDidMount: function (info) {
			content = '<strong>' + info.event.extendedProps.group_name + '</strong><br/>' + info.event.extendedProps.summary;
			$(info.el).tooltip({ title: content, html: true });
		},

		// store the current view type on each view change
		viewDidMount: function (info) {
			localStorage.setItem("fcDefaultView", info.view.type);
		}

	});
	calendar.render();
});


/*
A simple spinner that is shown when a request takes too long
*/
up.compiler('.js-spinner', function (element) {
	function show() { element.style.display = 'block' }
	function hide() { element.style.display = 'none' }
	up.on('up:proxy:slow', show)
	up.on('up:proxy:recover', hide)
	hide()
});

//up.proxy.config.slowDelay=300


up.compiler('.js-network-error', function (element) {
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
up.$compiler('.js-tags', function ($element, data) {
	$element.select2({
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

up.compiler('#last_read', function (element) {
	console.log(element);
	element.scrollIntoView({
		block: 'center',
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
		responsive: true,
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



/**
 * Add simple history.back behaviour onclick on element 
 */
up.compiler('.js-back', function (element) {

	element.onclick = function () {
		window.history.back();
		return false;
	};

});