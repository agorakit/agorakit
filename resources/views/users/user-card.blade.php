<a class="btn btn-outline-secondary" href="{{ route('users.show', [$user]) }}">
    <img alt="" class="avatar me-1 rounded" src="{{ route('users.cover', [$user, 'medium']) }}" />
    {{ $user->name }}
</a>
