<nav class="navbar navbar-expand-lg bg-dark sticky-top" data-bs-theme="dark" up-fixed="top">
    <div class="container-fluid">
        <!-- logo -->
        <a class="navbar-brand me-4" href="{{ route('index') }}">
            @if (Storage::exists('public/logo/favicon.png'))
                <img src="{{ asset('storage/logo/favicon.png') }}" width="40" height="40" />
            @else
                <img src="/images/logo-white.svg" width="40" height="40" />
            @endif
            <span class="d-none d-md-inline">{{ setting('name') }}</span>
        </a>


        <!-- Single dropdown on mobile to browse groups -->
        @auth
            @if (Auth::user()->groups()->count() > 0)
                <div class="dropdown d-lg-none">
                    <a class="dropdown-toggle nav-link fs-2" data-bs-toggle="dropdown" href="#" role="button"
                        aria-haspopup="true" aria-expanded="false">
                        {{ trans('messages.my_groups') }}
                    </a>
                    <div class="dropdown-menu">
                        @foreach (Auth::user()->groups()->orderBy('name')->get() as $group)
                            <a class="dropdown-item" href="{{ route('groups.show', $group) }}">{{ $group->name }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endauth

        <!-- navbar toggler hamburger -->
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbar" type="button"
            aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- collapsable navbar -->
        <div class="collapse navbar-collapse" id="navbar">

            <ul class="navbar-nav me-auto">
                @auth
                    @if (Auth::user()->groups()->count() > 0)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                                aria-haspopup="true" aria-expanded="false">
                                {{ trans('messages.my_groups') }}
                            </a>
                            <div class="dropdown-menu">
                                @foreach (Auth::user()->groups()->orderBy('name')->get() as $group)
                                    <a class="dropdown-item"
                                        href="{{ route('groups.show', $group) }}">{{ $group->name }}</a>
                                @endforeach
                            </div>
                        </li>
                    @endif
                @endauth

                <!-- Overview -->
                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-toggle="dropdown"
                        href="#" role="button" aria-expanded="false">
                        @lang('Overview')
                    </a>

                    <ul class="dropdown-menu">

                        <a class="dropdown-item" class="dropdown-item" href="{{ action('GroupController@index') }}">
                            {{ trans('messages.all_groups') }}
                        </a>

                        <a class="dropdown-item " href="{{ action('DiscussionController@index') }}">
                            {{ trans('messages.discussions') }}
                        </a>

                        <a class="dropdown-item" href="{{ action('ActionController@index') }}">
                            {{ trans('messages.agenda') }}
                        </a>
                        @auth
                            <a class="dropdown-item" href="{{ action('TagController@index') }}">
                                @lang('Tags')
                            </a>

                            <a class="dropdown-item" href="{{ action('MapController@index') }}">
                                {{ trans('messages.map') }}
                            </a>
                            <a class="dropdown-item" href="{{ action('FileController@index') }}">
                                {{ trans('messages.files') }}
                            </a>

                            <a class="dropdown-item" href="{{ action('UserController@index') }}">
                                {{ trans('messages.users_list') }}
                            </a>
                        @endauth
                    </ul>
                </li>

                <!-- help -->
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ action('PageController@help') }}">
                            {{ trans('messages.help') }}
                        </a>
                    </li>
                @endauth

                <!-- Notifications -->
                @auth
                    @if (isset($notifications))
                        <div class="dropdown hidden lg:block sm:px-4">
                            <a class="text-gray-200 px-1 d-flex flex-col justify-center align-items-center rounded-full  hover:bg-gray-600 bg-gray-700 h-12 w-12 sm:me-2 sm:px-4 sm:bg-transparent sm:rounded"
                                data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                                aria-expanded="false">
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

                <!-- locales -->
                @if (\Config::has('app.locales'))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                            aria-expanded="false">
                            Locale ({{ strtoupper(app()->getLocale()) }})
                        </a>

                        <ul class="dropdown-menu">
                            @foreach (\Config::get('app.locales') as $locale)
                                @if ($locale !== app()->getLocale())
                                    <li>
                                        <a class="dropdown-item"
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
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                                aria-expanded="false">
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

                                <a class="dropdown-item" href="{{ action('Admin\InsightsController@index') }}">
                                    <i class="fa fa-line-chart me-2"></i> {{ trans('messages.insights') }}
                                </a>

                                <a class="dropdown-item" href="{{ url('/admin/logs') }}">
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
                            <input value="{{ request()->get('query') }}" name="query"
                                class="form-control me-2 bg-light text-dark" type="search"
                                placeholder="{{ trans('messages.search') }}" aria-label="Search" />
                        </form>
                    </li>
                @endauth

                <!-- User profile -->
                @auth
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                            aria-expanded="false">
                            {{ trans('messages.profile') }} ({{ Auth::user()->name }})
                        </a>

                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}"><i
                                    class="fa fa-btn fa-user me-2"></i>
                                {{ trans('messages.profile') }}</a>
                            <a class="dropdown-item" href="{{ route('users.edit', Auth::user()) }}"><i
                                    class="fa fa-btn fa-user-edit me-2"></i>
                                {{ trans('messages.edit_my_profile') }}</a>

                            <a class="dropdown-item" href="{{ url('/logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
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
