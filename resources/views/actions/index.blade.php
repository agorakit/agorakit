@extends('app')

@section('footer')
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/lang-all.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>



<script>
$(document).ready(function() {
  $('#calendar').fullCalendar({
    lang: '{{App::getLocale()}}',
    events: '{{action('ActionController@indexJson', $group->id)}}',
    header: {
      left: 'prev,next',
      center: 'title',
      right: 'month,agendaWeek,agendaDay'
    },

    eventClick:  function(event, jsEvent, view) {
      $('#modalTitle').html(event.title);
      $('#modal-body').html(event.body);
      $('#modal-location').html(String(event.location));
      $('#modal-start').html(event.start.format('LLL'));
      $('#modal-stop').html(event.end.format('LLL'));
      $('#eventUrl').attr('href',String(event.url));
      $('#fullCalModal').modal();
      return false;
    },

    eventRender: function(event, element)
    {
      $(element).tooltip({title: event.summary});
    }
  });
});
</script>

@endsection

@section('content')

@include('partials.grouptab')

<div class="tab_content">


  <h2>{{trans('action.agenda_of_this_group')}}
    @can('create-action', $group)
    <a class="btn btn-primary btn-xs" href="{{ action('ActionController@create', $group->id ) }}">
      <i class="fa fa-plus"></i> {{trans('action.create_one_button')}}
    </a>
    @endcan

  </h2>


  <div class="spacer"></div>



  <div id="calendar"></div>

  <div id="fullCalModal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
          <h4 id="modalTitle" class="modal-title"></h4>
        </div>

        <div class="modal-body">
          <p>
            <strong>{{trans('messages.description')}}</strong> : <span id="modal-body"></span>
          </p>

          <strong>{{trans('messages.location')}} : </strong><span id="modal-location"></span>
          <br/>
          <strong>{{trans('messages.start')}} : </strong><span id="modal-start"></span>
          <br/>
          <strong>{{trans('messages.stop')}} : </strong><span id="modal-stop"></span>
          <br/>

        </div>

        <div class="modal-footer">
          <a class="btn btn-primary" id="eventUrl">{{trans('messages.details')}}</a>
        </div>

      </div>
    </div>
  </div>







</div>

@endsection
