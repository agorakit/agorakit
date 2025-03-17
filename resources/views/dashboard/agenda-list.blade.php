@extends('app')

@section('content')
    <div class="mb-2">
        @include('dashboard.tabs')
    </div>

    <div class="d-md-flex gap-2 align-items-center justify-content-between mb-2">
        <div class="mb-2">
            @include('partials.preferences-calendar')
        </div>
        <a class="btn btn-primary" href="{{ route('actions.create') }}">
            {{ trans('messages.create_action') }}
        </a>
    </div>



    @if ($actions->count() > 0)
        <div class="actions mb-4">
            @include('actions.list', ['actions' => $actions])
        </div>
        {{ $actions->render() }}
    @else
        <div class="alert mt-4">
            {{ trans('messages.nothing_yet') }}
        </div>
    @endif

    @include('dashboard.ical')
@endsection
