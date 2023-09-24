@extends('app')

@section('content')
    <div class="mb-2">
        @include('dashboard.tabs')
    </div>

    <div class="d-flex justify-content-between gap-2 flex-wrap">
        <div>
            @include('partials.preferences-calendar')
        </div>

        <div class="">

            <a class="btn btn-primary" href="{{ route('actions.create') }}">
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
