<div class="d-flex mb-4 pb-4 border-bottom" up-expand>
    <div class="me-3">
        @include ('users.avatar', ['user => $user'])
    </div>
    <div class="">

        <div class="fw-bold">
            <a href="{{ route('users.show', [$user]) }}"> {{ $user->name }} </a>
        </div>

        <div class="">{{ summary($user->body) }}</div>

        <div class="text-meta">
            {{ trans('messages.last_activity') }} : {{ $user->updated_at->diffForHumans() }}
        </div>
    </div>
</div>
