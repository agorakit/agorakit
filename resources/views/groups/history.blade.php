@extends('app')

@section('content')
    <h2>{{ trans('Edit history of this group') }}</h2>

    @foreach ($group->revisionHistory as $history)
        <p class="history">
            {{ $history->created_at->diffForHumans() }}, {{ $history->userResponsible()->name }} {{ trans('Changed') }} <strong>{{ $history->fieldName() }}</strong>
            {{ trans('Changed from') }} <code>{{ $history->oldValue() }}</code> {{ trans('Changed to') }} <code>{{ $history->newValue() }}</code>
        </p>
    @endforeach
@endsection
