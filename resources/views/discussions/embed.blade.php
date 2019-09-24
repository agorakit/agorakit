
<a href="{{ route('groups.discussions.show', [$discussion->group, $discussion]) }}">
  <div class="discussion-embed">
    <div class="title"><i class="far fa-comment"></i> {{ $discussion->name }}</div>
    <div class="summary">{{ summary($discussion->body) }}</div>
  </div>
</a>
