@if (isset($user))
    @if ($user->hasCover())
        <img class="avatar rounded-circle" src="{{ route('users.cover', [$user, 'small']) }}" title="{{ $user->name }}" style="max-width:40px;" width="40" height="40" />
    @else
        <img class="avatar rounded-circle" src="{{ Avatar::create($user->name)->setShape('square')->toBase64() }}" title="{{ $user->name }}" />
    @endif
@else
    <span class="avatar rounded-circle" title="Unknown user">?</span>
@endif
