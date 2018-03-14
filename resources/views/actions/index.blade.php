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
            events: '{{route('groups.actions.index.json', $group->id)}}',
            header: {
                left: 'prev,next',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },

            selectable: true,
            selectHelper: true,
            select: function(start, end) {
                var title = prompt('{{trans('messages.title')}}');
                var eventData;
                if (title) {
                    eventData = {
                        title: title,
                        start: start,
                        end: end
                    };
                    $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
                    window.location.href = "{{ route('groups.actions.create', $group->id ) }}?start=" + encodeURIComponent(start.format('YYYY-MM-DD HH:mm')) + "&stop=" + encodeURIComponent(end.format('YYYY-MM-DD HH:mm')) + "&title=" + encodeURIComponent(title);
                }
                $('#calendar').fullCalendar('unselect');
            },

            eventClick:  function(event, jsEvent, view) {
                /*
                up.modal.visit(event.url, { target: '.content' });
                return false;
                */

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

    @include('groups.tabs')

    <div class="tab_content">


        @auth
            <div class="toolbox d-md-flex mb-4">
                <div class="mb-2">
                    <div class="btn-group" role="group">
                        <a href="?type=grid" class="btn btn-primary"><i class="fa fa-calendar"></i> {{trans('messages.grid')}}</a>
                        <a href="?type=list" class="btn btn-outline-primary"><i class="fa fa-list"></i> {{trans('messages.list')}}</a>
                    </div>
                </div>

                @can('create-action', $group)
                    <div class="ml-auto">
                        <a class="btn btn-primary" href="{{ route('groups.actions.create', $group->id ) }}">
                            <i class="fa fa-plus"></i> {{trans('action.create_one_button')}}
                        </a>
                    </div>
                @endcan

            </div>
        @endauth




        <div id="calendar"></div>


        <p><a href="{{action('IcalController@group', $group->id)}}">Téléchargez le calendrier de ce groupe au format iCal</a></p>


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
