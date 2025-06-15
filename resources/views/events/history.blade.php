@extends('app')

@section('content')
    <h2>{{ trans('messages.history') }}</h2>

    @foreach ($event->revisionHistory as $history)
        <p class="history">
            {{ $history->created_at->diffForHumans() }}, {{ $history->userResponsible()->name }} {{ trans('messages.changed') }} <strong>{{ $history->fieldName() }}</strong>
            {{ trans('messages.changed_from') }} <code>{{ $history->oldValue() }}</code> {{ trans('messages.changed_to') }} <code>{{ $history->newValue() }}</code>
        </p>
    @endforeach

    <a class="btn btn-primary" href="{{ route('groups.events.show', [$group, $event]) }}" >{{ trans('messages.back_to') }} "{{ $event->name }}"</a>
@endsection
