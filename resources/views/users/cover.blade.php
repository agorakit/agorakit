@if (isset($user))
    <img class="rounded" src="{{ route('users.cover', [$user, 'medium']) }}" title="{{ $user->name }}" />
@else
    <span class="avatar rounded-circle" title="Unknown user">?</span>
@endif
