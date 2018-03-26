@extends('app')

@section('content')



    <div style="float:right">
        <a class="btn btn-primary" href="{{ route('actions.create') }}">
            <i class="fa fa-plus"></i> {{trans('action.create_one_button')}}
        </a>
    </div>

    <h1>
        <a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.agenda') }}
    </h1>

    @include ('partials.preferences-calendar')
    @include ('partials.preferences-show')



    <div class="mt-5" id="calendar"></div>
    <p><a href="{{action('IcalController@index')}}">{{trans('messages.download_ical')}}</a></p>



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
                $(element).tooltip({title: event.group_name + ' : ' + event.title + ' : ' + event.summary});
            }
        });
    });
    </script>

@endsection
