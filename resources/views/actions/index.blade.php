@extends('app')

@section('footer')
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>


<script>
  $(document).ready(function() {
    $('#calendar').fullCalendar({
      events: '{{action('ActionController@indexJson', $group->id)}}',
        header: {
            left: '',
            center: 'prev title next',
            right: ''
        },
        eventClick:  function(event, jsEvent, view) {
            $('#modalTitle').html(event.title);
            $('#modalBody').html(event.description);
            $('#eventUrl').attr('href',event.url);
            $('#fullCalModal').modal();
            return false;
        }
    });
});
</script>

@endsection

@section('content')

@include('partials.grouptab')

<div class="tab_content">


  <h2>{{trans('action.agenda_of_this_group')}} <a class="btn btn-primary btn-xs" href="{{ action('ActionController@create', $group->id ) }}"><i class="fa fa-plus"></i> {{trans('action.create_one_button')}}</a></h2>

  <div class="spacer"></div>



  <div id="calendar"></div>

  <div id="fullCalModal" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                  <h4 id="modalTitle" class="modal-title"></h4>
              </div>
              <div id="modalBody" class="modal-body"></div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button class="btn btn-primary"><a id="eventUrl" target="_blank">Event Page</a></button>
              </div>
          </div>
      </div>
  </div>







</div>

@endsection
