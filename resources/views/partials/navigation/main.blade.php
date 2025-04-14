<nav class="d-flex flex-sm-row align-items-center align-self-center mb-4 fs-1">

    <div class="nav-item me-4">
        <a href="{{ route('index') }}">
            <img alt="" height="40" src="{{ route('icon', 40) }}" width="40" />
        </a>
    </div>


    @if (Context::isGroup() || Context::isOverview())
        <div class="nav-item">
            @include('partials.navigation.context')
        </div>
    @endif

    @if (Context::is('user'))
        <div class="nav-item">
            {{ $user->name }} <em>({{ '@' . $user->username }})</em>
        </div>
    @endif

    <div class="nav-item ms-auto d-flex fs-3 align-self-center align-items-center">
        @auth
            <div class="me-3">
                @include('partials.navigation.profile')
            </div>
        @endauth

        <div>
            @include('partials.navigation.locales')
        </div>
    </div>
</nav>

<div>
    @if (Context::isGroup())
        @include('partials.tabs.groups')
    @endif
    @if (Context::isOverview())
        @include('partials.tabs.overview')
    @endif
    @if (Context::is('user'))
        @include('partials.tabs.users')
    @endif
</div>
