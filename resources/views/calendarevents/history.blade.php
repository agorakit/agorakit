@extends('app')

@section('content')
    <h2>{{ t('history') }}</h2>

    @foreach ($event->revisionHistory as $history)
        <p class="history">
            {{ $history->created_at->diffForHumans() }}, {{ $history->userResponsible()->name }} {{ t('Changed') }} <strong>{{ $history->fieldName() }}</strong>
            {{ t('Changed from') }} <code>{{ $history->oldValue() }}</code> {{ t('Changed to') }} <code>{{ $history->newValue() }}</code>
        </p>
    @endforeach

    <a class="btn btn-primary" href="{{ route('groups.calendarevents.show', [$group, $event]) }}" >{{ t('Back to') }} "{{ $event->name }}"</a>
@endsection
