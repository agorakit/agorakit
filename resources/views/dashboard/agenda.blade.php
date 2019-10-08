@extends('app')

@section('content')

  <div class="toolbox d-md-flex">
    <div class="d-flex mb-2">
      <h1>
        <a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.agenda') }}
      </hi>
    </div>

    <div class="ml-auto">
      @include ('partials.preferences-show')
    </div>
  </div>


  <div class="d-md-flex justify-content-between">


    <div class="my-2">
      <a class="btn btn-primary" href="{{ route('actions.create') }}">
        <i class="fa fa-plus"></i> {{trans('action.create_one_button')}}
      </a>
    </div>


    <div class="my-2">
      @include ('partials.preferences-calendar')
    </div>

  </div>



  <div class="mt-5" id="calendar"></div>

  @include('dashboard.ical')


@endsection

@section('footer')
  <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/lang-all.js"></script>
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>



  <script>
  $(document).ready(function() {
    $('#calendar').fullCalendar({
      lang: '{{App::getLocale()}}',
      events: '{{action('ActionController@indexJson')}}',
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
  </script>

@endsection
