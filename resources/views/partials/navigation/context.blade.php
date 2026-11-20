<div class="dropdown nav-item">
    <div aria-expanded="false" class="dropdown-toggle" data-bs-toggle="dropdown" type="button">
        @if (Context::isGroup())
            {{ $group->name }}
            <small class="fs-4 text-secondary">
                @if ($group->isOpen())
                    <i class="fa fa-lock-open" title="{{ t('Public group') }}"></i>
                @elseif ($group->isClosed())
                    <i class="fa fa-lock" title="{{ t('Closed group') }}"></i>
                @else
                    <i class="fa fa-eye-slash" title="{{ t('Secret group') }}"></i>
                @endif
            </small>
        @endif

        @if (Context::is('joined'))
            {{ t('My groups') }}
        @endif

        @if (Context::is('public'))
            {{ t('Public groups') }}
        @endif

        @if (Context::is('all'))
            {{ t('All groups (admin)') }}
        @endif

    </div>

    @auth
        <ul class="dropdown-menu">

            @if (Auth::user()->groups()->count() > 0)
                <li>
                    <h6 class="dropdown-header">{{ t('My groups') }}</h6>
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
                <h6 class="dropdown-header">{{t('Overview')}}</h6>
            </li>

            <a class="dropdown-item @if (Context::is('joined')) active @endif"
                href="{{ url()->query(url()->current(), ['set_preference' => 'show', 'value' => 'joined']) }}">
                {{ t('My groups') }}
            </a>

            <a class="dropdown-item
            @if (Context::is('public')) active @endif"
                href="{{ url()->query(url()->current(), ['set_preference' => 'show', 'value' => 'public']) }}">
                {{ t('Public groups') }}
            </a>

            @if (Auth::check() && Auth::user()->isAdmin())
                <a class="dropdown-item @if (Context::is('all')) active @endif"
                    href="{{ url()->query(url()->current(), ['set_preference' => 'show', 'value' => 'all']) }}">
                    {{ t('All groups (admin)') }}
                </a>
            @endif
        </ul>
    @endauth
</div>
