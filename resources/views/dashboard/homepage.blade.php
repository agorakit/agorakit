@extends('app')

@section('content')


<h1><a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ __('Homepage') }}</h1>
@include ('partials.preferences-show')


<div class="row">
    <div class="col-md-8">


        @if ($discussions->count() > 0)
        <h1 class="small"> {{ __('Latest discussions') }}</h1>
        <a class="badge badge-pill badge-primary mb-4" href="{{ route('discussions.create') }}">
            <i class="fa fa-plus"></i> {{trans('discussion.create_one_button')}}
        </a>


        <div class="discussions">
            @forelse( $discussions as $discussion )
            @include('discussions.discussion')
            @empty
            {{trans('messages.nothing_yet')}}
            @endforelse
        </div>

        @endif
    </div>


    <div class="col-md-4">

        @if ($actions->count() > 0)
        <h1 class="small">{{ __('Calendar') }}</h1>
        <a class="badge badge-pill badge-primary mb-4" href="{{ route('actions.create') }}">
            <i class="fa fa-plus"></i> {{trans('action.create_one_button')}}
        </a>

        <div class="actions">
            @forelse( $actions as $action)
            @include('actions.action')
            @empty
            {{trans('messages.nothing_yet')}}
            @endforelse
        </div>
        @endif
    </div>
</div>









@endsection
