<div id="live-content">
  @if ($comments->count() > 0)
  <div class="w-full d-flex justify-center my-4" id="unread">
            <div class="inline-block bg-red-700 text-red-100 rounded-full px-4 py-2 text-sm uppercase">
                <i class="far fa-arrow-alt-circle-down mr-2"></i> {{$comments->count()}} @lang('messages.unread')
            </div>
        </div>
  @endif

  @foreach ($comments as $comment_key => $comment)
    @include('comments.comment')
  @endforeach
</div>
