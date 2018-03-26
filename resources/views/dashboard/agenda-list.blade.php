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



    @if ($actions->count() > 0)
        <div class="actions">
            @foreach( $actions as $action)
                @include('actions.action')
            @endforeach
        </div>
        {{$actions->render()}}
    @else
        <div class="alert">
            {{trans('messages.nothing_yet')}}
        </div>
    @endif


    <p><a href="{{action('IcalController@index')}}">{{trans('messages.download_ical')}}</a></p>


@endsection
