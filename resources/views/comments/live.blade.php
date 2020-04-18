<div id="live-content">
  @if ($comments->count() > 0)
    <div class="text-center">
      <div class="btn btn-unread">{{$comments->count()}} @lang('unread message(s)')</div>
    </div>
  @endif

  @foreach ($comments as $comment_key => $comment)
    @include('comments.comment')
  @endforeach
</div>
