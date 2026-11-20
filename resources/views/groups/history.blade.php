@extends('app')

@section('content')
    <h2>{{ t('Edit history of this group') }}</h2>

    @foreach ($group->revisionHistory as $history)
        <p class="history">
            {{ $history->created_at->diffForHumans() }}, {{ $history->userResponsible()->name }} {{ t('Changed') }} <strong>{{ $history->fieldName() }}</strong>
            {{ t('Changed from') }} <code>{{ $history->oldValue() }}</code> {{ t('Changed to') }} <code>{{ $history->newValue() }}</code>
        </p>
    @endforeach
@endsection
