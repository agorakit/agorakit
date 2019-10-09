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
            events: '{{route('groups.actions.index.json', $group)}}',
            header: {
                left: 'prev,next',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },

            selectable: false,
            selectHelper: true,
            select: function(start, end) {
                up.modal.visit("{{ route('groups.actions.create', $group ) }}?start="
                 + encodeURIComponent(start.format('YYYY-MM-DD HH:mm'))
                 + "&stop="
                 + encodeURIComponent(end.format('YYYY-MM-DD HH:mm')),
                  { target: '.tab_content' });

                //$('#calendar').fullCalendar('unselect');
            },


            eventClick:  function(event, jsEvent, view) {
                up.modal.visit(event.url, { target: '.content' });
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
                        <a class="btn btn-primary" href="{{ route('groups.actions.create', $group ) }}">
                            <i class="fa fa-plus"></i> {{trans('action.create_one_button')}}
                        </a>
                    </div>
                @endcan

            </div>
        @endauth




        <div id="calendar"></div>


        @include('actions.ical')


    </div>

@endsection
