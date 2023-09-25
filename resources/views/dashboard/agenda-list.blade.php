@extends('app')

@section('content')
    <div class="mb-2">
        @include('dashboard.tabs')
    </div>

    <div class="d-flex flex-wrap gap-2 justify-content-between mb-2">
        <a class="btn btn-primary" href="{{ route('actions.create') }}">
            <span class="hidden md:inline ml-2">
                {{ trans('action.create_one_button') }}
            </span>
        </a>
    </div>

    @include('partials.preferences-calendar')
    
    @if ($actions->count() > 0)
        <div class="actions mt-4">
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
