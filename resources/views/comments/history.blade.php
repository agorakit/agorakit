@extends('app')

@section('content')
  <h2>{{trans('history')}}</h2>

  @foreach($comment->revisionHistory as $history )
  <p class="history">
  {{ $history->created_at->diffForHumans() }}, {{ $history->userResponsible()->name }} {{trans('Changed')}} <strong>{{ $history->fieldName() }}</strong> {{trans('Changed from')}} <code>{{ $history->oldValue() }}</code> {{trans('Changed to')}} <code>{{ $history->newValue() }}</code>
</p>
  @endforeach

<a  href="{{route('groups.discussions.show', [$group, $comment->discussion])}}#unread" class="btn btn-primary">{{trans('Back to')}} "{{$comment->discussion->name}}"</a>

@endsection
