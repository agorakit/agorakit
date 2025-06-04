@extends('app')

@section('content')
  <h2>{{trans('messages.history')}}</h2>

  @foreach($comment->revisionHistory as $history )
  <p class="history">
  {{ $history->created_at->diffForHumans() }}, {{ $history->userResponsible()->name }} {{trans('messages.changed')}} <strong>{{ $history->fieldName() }}</strong> {{trans('messages.changed_from')}} <code>{{ $history->oldValue() }}</code> {{trans('messages.changed_to')}} <code>{{ $history->newValue() }}</code>
</p>
  @endforeach

<a  href="{{route('groups.discussions.show', [$group, $comment->discussion])}}#unread" class="btn btn-primary">{{trans('messages.back_to')}} "{{$comment->discussion->name}}"</a>

@endsection
