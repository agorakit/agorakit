@extends('app')

@section('content')
  <h2>{{t('history')}}</h2>

  @foreach($discussion->revisionHistory as $history )
  <p class="history">
  {{ $history->created_at->diffForHumans() }}, {{ $history->userResponsible()->name }} {{t('Changed')}} <strong>{{ $history->fieldName() }}</strong> {{t('Changed from')}} <code>{{ $history->oldValue() }}</code> {{t('Changed to')}} <code>{{ $history->newValue() }}</code>
</p>
  @endforeach

<a  href="{{route('groups.discussions.show', [$group, $discussion])}}#unread" class="btn btn-primary">{{t('Back to')}} "{{$discussion->name}}"</a>

@endsection
