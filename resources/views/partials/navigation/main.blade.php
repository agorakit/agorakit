<nav class="nav d-flex flex-sm-row align-items-center align-self-center mb-4 fs-1">

    <div class="nav-item me-3">
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
            <!-- Admin -->
            @if (Auth::user()->isAdmin())
                <div class="nav-item dropdown">
                    <a aria-expanded="false" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
                        {{ trans('messages.server_administration') }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-end" role="menu">

                        <a class="dropdown-item" href="{{ url('/admin/settings') }}">
                            <i class="fa fa-cog me-2"></i> {{ trans('messages.settings') }}
                        </a>

                        <a class="dropdown-item" href="{{ url('/admin/user') }}">
                            <i class="fa fa-users me-2"></i> {{ trans('messages.users') }}
                        </a>

                        <a class="dropdown-item" href="{{ url('/admin/groupadmins') }}">
                            <i class="fa fa-users me-2"></i> {{ trans('messages.group_admins') }}
                        </a>

                        <a class="dropdown-item" href="{{ url('/admin/undo') }}">
                            <i class="fa fa-trash me-2"></i> {{ trans('messages.recover_content') }}
                        </a>

                        <a class="dropdown-item" href="{{ action('Admin\InsightsController@index') }}" up-follow="false">
                            <i class="fa fa-line-chart me-2"></i> {{ trans('messages.insights') }}
                        </a>

                        <a class="dropdown-item" href="{{ url('/admin/logs') }}" up-follow="false">
                            <i class="fa fa-keyboard-o me-2"></i> {{ trans('messages.logs') }}
                        </a>
                    </div>
                </div>
            @endif

            <div class="">
                @include('partials.navigation.profile')
            </div>
        @endauth

        @guest
            <li class="nav-item">
                <a class="nav-link" href="{{ url('login') }}" up-layer="new">
                    {{ trans('messages.login') }}
                </a>
            </li>

            @can('create', App\User::class)
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('register') }}" up-layer="new">
                        {{ trans('messages.register') }}
                    </a>
                </li>
            @endcan
        @endguest

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
