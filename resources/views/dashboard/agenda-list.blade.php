@extends('app')

@section('content')
    <div class="d-flex">
        <h1>
            <a  href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
            {{ trans('messages.agenda') }}
        </h1>
    </div>

    <div class="flex justify-content-between">

        <div class="flex ">
            <div class="mr-4">
                @include('partials.preferences-show')
            </div>
            <div>
                @include('partials.preferences-calendar')
            </div>
        </div>

        <div class="">
            <a class="btn btn-primary" href="{{ route('actions.create') }}">
                <i class="fas fa-pencil-alt"></i>
                <span class="hidden md:inline ml-2">
                    {{ trans('action.create_one_button') }}
                </span>
            </a>
        </div>

    </div>

    @if ($actions->count() > 0)
        <div class="actions my-5">
            @include('actions.list', ['actions' => $actions])
        </div>
        {{ $actions->render() }}
    @else
        <div class="alert">
            {{ trans('messages.nothing_yet') }}
        </div>
    @endif

    @include('dashboard.ical')
@endsection
