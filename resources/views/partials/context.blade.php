<div class="dropdown">
    <h1 aria-expanded="false" class="dropdown-toggle" data-bs-toggle="dropdown" type="button">
        @if (Context::isGroup())
            {{ $group->name }}
            <small class="fs-4 text-secondary">
                @if ($group->isOpen())
                    <i class="fa fa-lock-open" title="{{ trans('group.open') }}"></i>
                @elseif ($group->isClosed())
                    <i class="fa fa-lock" title="{{ trans('group.closed') }}"></i>
                @else
                    <i class="fa fa-eye-slash" title="{{ trans('group.secret') }}"></i>
                @endif
            </small>
        @endif

        @if (Context::is('my'))
            {{ __('messages.overview_my_groups') }}
        @endif

        @if (Context::is('public'))
            {{ __('messages.overview_public_groups') }}
        @endif

        @if (Context::is('admin'))
            {{ __('messages.overview_admin_groups') }}
        @endif

    </h1>

    @auth
        <ul class="dropdown-menu">

            @if (Auth::user()->groups()->count() > 0)
                <li>
                    <h6 class="dropdown-header">{{ trans('messages.my_groups') }}</h6>
                </li>

                @foreach (Auth::user()->groups as $group)
                    <a class="dropdown-item @if (Context::is($group)) active @endif"
                        href="{{ route('groups.show', $group) }}">{{ $group->name }}</a>
                @endforeach

                <li>
                    <hr class="dropdown-divider">
                </li>
            @endif

            <li>
                <h6 class="dropdown-header">Overview</h6>
            </li>

            <a class="dropdown-item @if (Context::is('my')) active @endif"
                href="{{ route('index', ['set_preference' => 'show', 'value' => 'my']) }}">
                {{ __('messages.overview_my_groups') }}
            </a>

            <a class="dropdown-item
            @if (Context::is('public')) active @endif"
                href="{{ route('index', ['set_preference' => 'show', 'value' => 'public']) }}">
                {{ __('messages.overview_public_groups') }}
            </a>

            @if (Auth::check() && Auth::user()->isAdmin())
                <a class="dropdown-item @if (Context::is('admin')) active @endif"
                    href="{{ route('index', ['set_preference' => 'show', 'value' => 'admin']) }}">
                    {{ __('messages.overview_admin_groups') }}
                </a>
            @endif
        </ul>
    @endauth
</div>
