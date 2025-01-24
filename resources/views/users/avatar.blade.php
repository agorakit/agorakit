@if (isset($user))
    <img alt="" class="avatar rounded-circle" src="{{ route('users.cover', [$user, 'small']) }}" title="{{ $user->name }}" style="max-width:40px;" width="40" height="40" />
@else
    <span class="avatar rounded-circle" title="Unknown user">?</span>
@endif
