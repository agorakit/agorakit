<nav class="navbar navbar-expand-lg bg-dark sticky-top" data-bs-theme="dark" up-fixed="top">
    <div class="container-fluid">
        <!-- logo -->
        <a class="navbar-brand me-4" href="{{ route('index') }}">
            <img src="{{ route('icon', 40) }}" height="40" width="40" />
            <span class="d-none d-md-inline">{{ setting('name') }}</span>
        </a>

        @php
            $groups = Auth::check() ? Auth::user()->groups()->orderBy('name')->get() : collect([]);
            $pinned_groups = $groups->filter(fn($g) => $g->settings['pinned_navbar'] ?? false);
            $overview_groups = $groups->filter(fn($g) => !in_array($g->id, $pinned_groups->pluck('id')->toArray()));
        @endphp

        <!-- Single dropdown on mobile to browse groups -->
        @auth
            @if (Auth::user()->groups()->count() > 0)
                <div class="dropdown d-lg-none">
                    <a class="dropdown-toggle nav-link fs-2" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" aria-haspopup="true">
                        {{ trans('messages.my_groups') }}
                    </a>
                    <div class="dropdown-menu">
                        @foreach ($overview_groups as $group)
                            <a class="dropdown-item" href="{{ route('groups.show', $group) }}">{{ $group->name }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endauth

        <!-- navbar toggler hamburger -->
        <button class="navbar-toggler" data-bs-target="#navbar" data-bs-toggle="collapse" type="button" aria-controls="navbar" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- collapsable navbar -->
        <div class="collapse navbar-collapse" id="navbar">

            <ul class="navbar-nav me-auto">
                @auth
                    @if (Auth::user()->groups()->count() > 0)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" aria-haspopup="true">
                                {{ trans('messages.my_groups') }}
                            </a>
                            <div class="dropdown-menu">
                                @foreach ($overview_groups as $group)
                                    <a class="dropdown-item" href="{{ route('groups.show', $group) }}">{{ $group->name }}</a>
                                @endforeach
                            </div>
                        </li>
                    @endif
                @endauth

                <!-- Overview -->
                <li class="nav-item dropdown">
                    @if (setting('show_overview_inside_navbar', true))
                        <a class="nav-link dropdown-toggle show_overview_inside_navbar" data-bs-toggle="dropdown" data-bs-toggle="dropdown" href="#" role="button"
                            aria-expanded="false">
                            @lang('Overview')
                        </a>
                    @endif
                    <ul class="dropdown-menu">
                        @if (setting('show_overview_all_groups', true))
                            <a class="dropdown-item messages.all_groups" href="{{ action('GroupController@index') }}">
                                {{ trans('messages.all_groups') }}
                            </a>
                        @endif
                        @if (setting('show_overview_discussions', true))
                            <a class="dropdown-item messages.discussions " href="{{ action('DiscussionController@index') }}">
                                {{ trans('messages.discussions') }}
                            </a>
                        @endif
                        @if (setting('show_overview_agenda', true))
                            <a class="dropdown-item messages.agenda" href="{{ action('ActionController@index') }}">
                                {{ trans('messages.agenda') }}
                            </a>
                        @endif
                        @auth
                            @if (setting('show_overview_tags', true))
                                <a class="dropdown-item messages.tags" href="{{ action('TagController@index') }}">
                                    @lang('Tags')
                                </a>
                            @endif
                            @if (setting('show_overview_map', true))
                                <a class="dropdown-item messages.map" href="{{ action('MapController@index') }}">
                                    {{ trans('messages.map') }}
                                </a>
                            @endif
                            @if (setting('show_overview_files', true))
                                <a class="dropdown-item messages.files" href="{{ action('FileController@index') }}">
                                    {{ trans('messages.files') }}
                                </a>
                            @endif
                            @if (setting('show_overview_users', true))
                                <a class="dropdown-item messages.users_list" href="{{ action('UserController@index') }}">
                                    {{ trans('messages.users_list') }}
                                </a>
                            @endif
                        @endauth
                    </ul>
                </li>

                <!-- pinned groups -->
                @auth
                    @if (count($pinned_groups->toArray()))
                        @foreach ($pinned_groups as $group)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('groups.show', $group) }}">{{ $group->name }}</a>
                            </li>
                        @endforeach
                    @endif
                @endauth

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
                            <a class="text-gray-200 px-1 d-flex flex-col justify-center align-items-center rounded-full  hover:bg-gray-600 bg-gray-700 h-12 w-12 sm:me-2 sm:px-4 sm:bg-transparent sm:rounded"
                                data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" aria-haspopup="true">
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
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                            Locale ({{ strtoupper(app()->getLocale()) }})
                        </a>

                        <ul class="dropdown-menu">
                            @foreach (\Config::get('app.locales') as $locale)
                                @if ($locale !== app()->getLocale() and setting("show_locale_{$locale}", true))
                                    <li>
                                        <a class="dropdown-item locale-{{ $locale }}" href="{{ Request::url() }}?force_locale={{ $locale }}">
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
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                                Admin & settings
                            </a>

                            <div class="dropdown-menu" role="menu">

                                <a class="dropdown-item" href="{{ url('/admin/settings') }}">
                                    <i class="fa fa-cog me-2"></i> Settings
                                </a>

                                <a class="dropdown-item" href="{{ url('/admin/user') }}">
                                    <i class="fa fa-users me-2"></i> Users
                                </a>

                                <a class="dropdown-item" href="{{ url('/admin/groupadmins') }}">
                                    <i class="fa fa-users me-2"></i> Group admins
                                </a>

                                <a class="dropdown-item" href="{{ url('/admin/undo') }}">
                                    <i class="fa fa-trash me-2"></i> Recover content
                                </a>

                                <a class="dropdown-item" href="{{ action('Admin\InsightsController@index') }}" up-follow="false">
                                    <i class="fa fa-line-chart me-2"></i> {{ trans('messages.insights') }}
                                </a>

                                <a class="dropdown-item" href="{{ url('/admin/logs') }}" up-follow="false">
                                    <i class="fa fa-keyboard-o me-2"></i> Logs
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
                        <form class="d-flex" role="search" action="{{ url('search') }}" method="get">
                            <input class="form-control me-2 bg-light text-dark" name="query" type="search" value="{{ request()->get('query') }}" aria-label="Search"
                                placeholder="{{ trans('messages.search') }}" />
                        </form>
                    </li>
                @endauth

                <!-- User profile -->
                @auth
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                            {{ trans('messages.profile') }} ({{ Auth::user()->name }})
                        </a>

                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}"><i class="fa fa-btn fa-user me-2"></i>
                                {{ trans('messages.profile') }}</a>
                            <a class="dropdown-item" href="{{ route('users.edit', Auth::user()) }}"><i class="fa fa-btn fa-user-edit me-2"></i>
                                {{ trans('messages.edit_my_profile') }}</a>

                            <a class="dropdown-item" href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="fa fa-btn fa-sign-out  me-2"></i> {{ trans('messages.logout') }}
                            </a>

                            <form id="logout-form" style="display: none;" action="{{ url('/logout') }}" method="POST">
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
