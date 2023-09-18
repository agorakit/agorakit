@extends('group')

@section('content')
    <h2>{{ trans('messages.history') }}</h2>

    @foreach ($action->revisionHistory as $history)
        <p class="history">
            {{ $history->created_at->diffForHumans() }}, {{ $history->userResponsible()->name }} {{ trans('messages.changed') }} <strong>{{ $history->fieldName() }}</strong>
            {{ trans('messages.changed_from') }} <code>{{ $history->oldValue() }}</code> {{ trans('messages.changed_to') }} <code>{{ $history->newValue() }}</code>
        </p>
    @endforeach

    <a class="btn btn-primary" href="{{ route('groups.actions.show', [$group, $action]) }}" >{{ trans('messages.back_to') }} "{{ $action->name }}"</a>
@endsection
