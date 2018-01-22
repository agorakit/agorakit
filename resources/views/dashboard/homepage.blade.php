@extends('app')

@section('content')

    <div class="tab_content">

        {{--@include('dashboard.tabs')--}}

        {{--
        <div class="spacer"></div>


        <div class="card text-white bg-info mb-3">
        <div class="card-body">
        <!--<h5 class="card-title">Info</h5>-->
        <p class="card-text">    {!! setting('homepage_presentation_for_members', trans('documentation.intro')) !!}</p>
    </div>
</div>
--}}


<div class="row">

    <div class="col-md-8">
        <div class="toolbox">
            <a class="btn btn-outline-primary btn-sm" href="{{ route('discussions.create') }}">
                <i class="fa fa-plus"></i> {{trans('discussion.create_one_button')}}
            </a>
        </div>

        @if ($my_discussions->count() > 0)

            <!--<h4>{{ trans('messages.latest_discussions_my') }} <a class="btn btn-outline-primary btn-sm" href="{{ route('discussions.create') }}">
                <i class="fa fa-plus"></i> {{trans('discussion.create_one_button')}}
            </a></h4>-->
            <div class="discussions">
                @forelse( $my_discussions as $discussion )
                    @include('discussions.discussion')
                @empty
                    {{trans('messages.nothing_yet')}}
                @endforelse
            </div>

        @endif
    </div>


    <div class="col-md-4">
        <div class="toolbox">
            <a class="btn btn-outline-primary btn-sm" href="{{ route('actions.create') }}">
                <i class="fa fa-plus"></i> {{trans('action.create_one_button')}}
            </a>
        </div>
        @if ($my_actions->count() > 0)

            <!--<h2>{{ trans('messages.agenda_my') }}</h2>-->
            <div class="actions">
                @forelse( $my_actions as $action)
                    @include('actions.action')
                @empty
                    {{trans('messages.nothing_yet')}}
                @endforelse
            </div>
        @endif
    </div>
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
