@extends('app')

@section('content')
    <h2>{{ trans('history') }}</h2>

    @foreach ($event->revisionHistory as $history)
        <p class="history">
            {{ $history->created_at->diffForHumans() }}, {{ $history->userResponsible()->name }} {{ trans('Changed') }} <strong>{{ $history->fieldName() }}</strong>
            {{ trans('Changed from') }} <code>{{ $history->oldValue() }}</code> {{ trans('Changed to') }} <code>{{ $history->newValue() }}</code>
        </p>
    @endforeach

    <a class="btn btn-primary" href="{{ route('groups.calendarevents.show', [$group, $event]) }}" >{{ trans('Back to') }} "{{ $event->name }}"</a>
@endsection
