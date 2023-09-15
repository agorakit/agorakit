<div class="card" style="max-width: 30rem;">
  <div class="card-body">
    <h5 class="card-title"><i class="far fa-comment"></i> {{ $discussion->name }}</h5>
    <p class="card-text">{{ summary($discussion->body) }}</p>
    <a  href="{{ route('groups.discussions.show', [$discussion->group, $discussion]) }}" class="btn btn-primary btn-sm">
      {{trans('messages.visit')}}
    </a>
  </div>
</div>
