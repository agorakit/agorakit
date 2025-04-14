<div class="dropdown">
    <h1 aria-expanded="false" class="dropdown-toggle" data-bs-toggle="dropdown" type="button">
        @if ($group->exists)
            {{ $group->name }}
        @elseif (Auth::user()->getPreference('show', 'my') == 'my')
            {{ __('Show my groups') }}
        @elseif (Auth::user()->getPreference('show', 'my') == 'all')
            {{ __('Show all groups') }}
        @elseif (Auth::user()->getPreference('show', 'my') == 'admin')
            {{ __('Show all groups (admin overview)') }}
        @endif

    </h1>

    <ul class="dropdown-menu">
        <a class="dropdown-item @if ($group->exists) active @endif" href="{{ $group->link() }}">
            {{ $group->name }}
        </a>

        <a class="dropdown-item @if (Auth::user()->getPreference('show', 'my') == 'my') active @endif" href="?set_preference=show&value=my">
            {{ __('Show my groups') }}
        </a>

        <a class="dropdown-item @if (Auth::user()->getPreference('show', 'my') == 'all') active @endif" href="?set_preference=show&value=all">
            {{ __('Show all groups') }}
        </a>

        @if (Auth::user()->isAdmin())
            <a class="dropdown-item @if (Auth::user()->getPreference('show', 'my') == 'admin') active @endif" href="?set_preference=show&value=admin">
                {{ __('Show all groups (admin overview)') }}
            </a>
        @endif
    </ul>
</div>