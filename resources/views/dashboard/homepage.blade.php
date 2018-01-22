@extends('app')

@section('content')

    <div class="tab_content">

        {{--@include('dashboard.tabs')--}}

        <div class="spacer"></div>

        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <!--<h5 class="card-title">Info</h5>-->
                <p class="card-text">    {!! setting('homepage_presentation_for_members', trans('documentation.intro')) !!}</p>
            </div>
        </div>


        <div class="row">


            @if ($my_discussions->count() > 0)
                <div class="col-md-8">
                    <h2>{{ trans('messages.latest_discussions_my') }}</h2>
                    <div class="discussions">
                        @forelse( $my_discussions as $discussion )
                            @include('discussions.discussion')
                        @empty
                            {{trans('messages.nothing_yet')}}
                        @endforelse
                    </div>
                </div>
            @endif



            @if ($my_actions->count() > 0)
                <div class="col-md-4">
                    <h2>{{ trans('messages.agenda_my') }}</h2>
                    <div class="actions">
                        @forelse( $my_actions as $action)
                            @include('actions.action')
                        @empty
                            {{trans('messages.nothing_yet')}}
                        @endforelse
                    </div>
                </div>
            @endif

        </div>

{{--
        <div class="row">


            @if ($other_discussions->count() > 0)
                <div class="col-md-8">
                    <h2>{{ trans('messages.latest_discussions_my') }}</h2>
                    <div class="discussions">
                        @forelse( $other_discussions as $discussion )
                            @include('discussions.discussion')
                        @empty
                            {{trans('messages.nothing_yet')}}
                        @endforelse
                    </div>
                </div>
            @endif



            @if ($other_actions->count() > 0)
                <div class="col-md-4">
                    <h2>{{ trans('messages.agenda_my') }}</h2>
                    <div class="actions">
                        @forelse( $other_actions as $action)
                            @include('actions.action')
                        @empty
                            {{trans('messages.nothing_yet')}}
                        @endforelse
                    </div>
                </div>
            @endif



        </div>

--}}

    </div>

@endsection
