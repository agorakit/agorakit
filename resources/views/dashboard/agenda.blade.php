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
    events: '{{action('DashboardController@agendaJson')}}',
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

      $('#modal-group-url').attr('href',String(event.group_url));
      $('#modal-group-url').html(String(event.group_name));

      $('#fullCalModal').modal();
      return false;
    },
  eventRender: function(event, element)
  {
    $(element).tooltip({title: event.body});
  }
});
});
</script>

@endsection



@section('content')



<div class="page_header">
  <h1>{{ trans('messages.agenda') }}</a></h1>
  <p>Si vous souhaitez ajouter des événements, rendez vous dans l'agenda d'un groupe précis</p>
</div>

<div class="tab_content">
<div id="calendar"></div>
</div>

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

        <strong>{{trans('messages.agenda')}} : </strong><a id="modal-group-url" target="_blank"></a>
        <br/>
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

<!--

@if ($actions)
    @foreach( $actions as $action )
    <div class="action">
      <h2 class="name">
        {{ $action->name }}</a>
      </h2>

      <div class="meta">{{trans('messages.started_by')}} <span class="user">{{ $action->user->name}}</span>, {{trans('messages.in')}} {{ $action->group->name}} {{ $action->created_at->diffForHumans()}} </div>

      <h4>{{trans('messages.what')}} ?</h4>
      <p class="body">
        {{ $action->body }}
      </p>
      <p>{{trans('messages.begins')}} : {{$action->start->format('d/m/Y H:i')}}</p>
      <p>{{trans('messages.ends')}} : {{$action->stop->format('d/m/Y H:i')}}</p>
      <p>{{trans('messages.location')}} : {{$action->location}}</p>

    </div>
    @endforeach

  </tbody>
</table>
@else
{{trans('messages.nothing_yet')}}
@endif

-->







@endsection
