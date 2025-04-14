<div class="dropdown">
    <h1 aria-expanded="false" class="dropdown-toggle" data-bs-toggle="dropdown" type="button">
        @if (Context::isGroup())
            {{ $group->name }}
        @endif

        @if (Context::is('my'))
            {{ __('messages.overview_my_groups') }}
        @endif

        @if (Context::is('public'))
            {{ __('messages.overview_public_groups') }}
        @endif

        @if (Context::is('admin'))
            {{ __('messages.overview_admin__groups') }}
        @endif

    </h1>

    <ul class="dropdown-menu">
        @if (Context::isGroup())
            <a class="dropdown-item @if ($group->exists) active @endif" href="{{ $group->link() }}">
                {{ $group->name }}
            </a>
        @endif

        <a class="dropdown-item @if (Context::is('my')) active @endif" href="?set_preference=show&value=my">
            {{ __('messages.overview_my_groups') }}
        </a>

        <a class="dropdown-item @if (Context::is('public')) active @endif" href="?set_preference=show&value=public">
            {{ __('messages.overview_public_groups') }}
        </a>

        @if (Auth::check() && Auth::user()->isAdmin())
            <a class="dropdown-item @if (Context::is('admin')) active @endif" href="?set_preference=show&value=admin">
                {{ __('messages.overview_admin_groups') }}
            </a>
        @endif
    </ul>
</div>
