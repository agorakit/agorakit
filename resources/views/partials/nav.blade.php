<nav class="navbar navbar-expand-lg" up-fixed="top">
    <div class="container-fluid">
        <!-- logo -->
        <a class="navbar-brand me-4" href="{{ route('index') }}">
            <img alt="" height="40" src="{{ route('icon', 40) }}" width="40" />
            <span class="d-none d-md-inline">{{ setting('name') }}</span>
        </a>

        <!-- navbar toggler hamburger -->
        <button aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-bs-target="#navbar"
            data-bs-toggle="collapse" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- collapsable navbar -->
        <div class="collapse navbar-collapse" id="navbar">

            <ul class="navbar-nav me-auto">
                <!-- help -->
                @auth
                    @if (setting('show_help_inside_navbar', true))
                        <li class="nav-item">
                            <a class="nav-link messages.help" href="{{ action('PageController@help') }}">
                                {{ trans('messages.help') }}
                            </a>
                        </li>
                    @endif
                @endauth

                <!-- Notifications -->
                @auth
                    @if (isset($notifications))
                        <div class="dropdown hidden lg:block sm:px-4">
                            <a aria-expanded="false" aria-haspopup="true"
                                class="text-gray-200 px-1 d-flex flex-col justify-center align-items-center rounded-full  hover:bg-gray-600 bg-gray-700 h-12 w-12 sm:me-2 sm:px-4 sm:bg-transparent sm:rounded"
                                data-bs-toggle="dropdown" href="#" role="button">
                                <i class="fas fa-bell"></i>
                            </a>
                            <div class="dropdown-menu-end rounded shadow">
                                @foreach ($notifications as $notification)
                                    <a class="dropdown-item">
                                        @if ($notification->type == 'App\Notifications\GroupCreated')
                                            @include('notifications.group_created')
                                        @endif

                                        @if ($notification->type == 'App\Notifications\MentionedUser')
                                            @include('notifications.mentioned_user')
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endauth

                @if (\Config::has('app.locales') and setting('show_locales_inside_navbar', true))
                    <!-- locales -->
                    <li class="nav-item dropdown">
                        <a aria-expanded="false" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
                            Locale ({{ strtoupper(app()->getLocale()) }})
                        </a>

                        <ul class="dropdown-menu">
                            @foreach (\Config::get('app.locales') as $locale)
                                @if ($locale !== app()->getLocale() and setting("show_locale_{$locale}", true))
                                    <li>
                                        <a class="dropdown-item locale-{{ $locale }}"
                                            href="{{ Request::url() }}?force_locale={{ $locale }}">
                                            {{ strtoupper($locale) }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif

                @auth
                    <!-- Admin -->
                    @if (Auth::user()->isAdmin())
                        <div class="nav-item dropdown">
                            <a aria-expanded="false" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
                                {{ trans('messages.server_administration') }}
                            </a>

                            <div class="dropdown-menu" role="menu">

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

            </ul>
            <ul class="navbar-nav">
                <!-- search-->
                @auth
                    <li class="nav-item d-lg-none d-xl-inline mt-2">
                        <form action="{{ url('search') }}" class="d-flex" method="get" role="search">
                            <input aria-label="Search" class="form-control me-2 bg-light text-dark" name="query"
                                placeholder="{{ trans('messages.search') }}" type="search" value="{{ request()->get('query') }}" />
                        </form>
                    </li>
                @endauth

                <!-- User profile -->
                @auth
                    <div class="nav-item dropdown">
                        <a aria-expanded="false" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
                            {{ trans('messages.profile') }} ({{ Auth::user()->name }})
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" role="menu">
                            <a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}"><i class="fa fa-btn fa-user me-2"></i>
                                {{ trans('messages.profile') }}</a>
                            <a class="dropdown-item" href="{{ route('users.edit', Auth::user()) }}"><i
                                    class="fa fa-btn fa-user-edit me-2"></i>
                                {{ trans('messages.edit_my_profile') }}</a>

                            <a class="dropdown-item" href="{{ url('/logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="fa fa-btn fa-sign-out  me-2"></i> {{ trans('messages.logout') }}
                            </a>

                            <form action="{{ url('/logout') }}" id="logout-form" method="POST" style="display: none;">
                                @csrf
                                @honeypot
                            </form>

                        </div>

                    </div>
                @endauth
            </ul>

        </div>
    </div>
</nav>
