<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container-fluid">
        <!-- logo -->
        <a class="navbar-brand" href="{{ route('index') }}">
            @if (Storage::exists('public/logo/favicon.png'))
                <img src="{{ asset('storage/logo/favicon.png') }}" width="30" height="24" />
            @else
                <img src="/images/logo-white.svg" width="30" height="24" />
            @endif
            <span class="">{{ setting('name') }}</span>
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbar" type="button"
            aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav">

                @auth
                    <li class="nav-item">
                        <div class="dropdown">
                            <a class="nav-item" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                                aria-expanded="false">

                                <i class="fa fa-cubes text-lg sm:hidden"></i>
                                <span class="hidden sm:inline">
                                    {{ trans('messages.groups') }}
                                    <i class="fa fa-caret-down"></i>
                                </span>

                            </a>
                            <div class="dropdown-menu rounded shadow">

                                @if (Auth::user()->groups()->count() > 0)
                                    <h6 class="dropdown-header">{{ trans('messages.my_groups') }}</h6>

                                    @foreach (Auth::user()->groups()->orderBy('name')->get() as $group)
                                        <a class="dropdown-item" href="{{ route('groups.show', $group) }}"
                                            up-target="body">{{ $group->name }}</a>
                                    @endforeach

                                    <div class="dropdown-divider"></div>
                                @endif

                                <a class="dropdown-item" class="dropdown-item" href="{{ action('GroupController@index') }}"
                                    up-target="body">
                                    <i class="fa fa-layer-group"></i> {{ trans('messages.all_groups') }}
                                </a>

                                @can('create', \App\Group::class)
                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" href="{{ route('groups.create') }}" up-target="body">
                                        {{ trans('group.create_a_group_button') }}
                                    </a>
                                @endcan

                            </div>
                        </div>

                    </li>

                @endauth

                <!-- Overview -->
                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" data-bs-toggle="dropdown" href="#"
                        role="button" aria-expanded="false">
                        <i class="fa fa-university"></i>
                        @lang('Overview')
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item">@lang('Overview')</a>
                        </li>
                        <a class="dropdown-item" class="dropdown-item" href="{{ action('GroupController@index') }}">
                            <i class="fa fa-layer-group"></i> {{ trans('messages.all_groups') }}

                        </a>

                        <a class="dropdown-item " href="{{ action('DiscussionController@index') }}">
                            <i class="fa fa-comments-o"></i> {{ trans('messages.discussions') }}
                        </a>

                        <a class="dropdown-item" href="{{ action('ActionController@index') }}">
                            <i class="fa fa-calendar"></i> {{ trans('messages.agenda') }}
                        </a>

                        <a class="dropdown-item" href="{{ action('TagController@index') }}">
                            <i class="fa fa-tag"></i> @lang('Tags')
                        </a>

                        <a class="dropdown-item" href="{{ action('MapController@index') }}">
                            <i class="fa fa-map-marker"></i> {{ trans('messages.map') }}
                        </a>
                        <a class="dropdown-item" href="{{ action('FileController@index') }}">
                            <i class="fa fa-files-o"></i> {{ trans('messages.files') }}
                        </a>

                        <a class="dropdown-item" href="{{ action('UserController@index') }}">
                            <i class="fa fa-users"></i> {{ trans('messages.users_list') }}
                        </a>
                    </ul>
                </li>

                <!-- help -->
                @auth
                    <li class="nav-item">
                        <a class="text-gray-200 px-1 flex flex-col justify-center items-center rounded-full  hover:bg-gray-600 bg-gray-700 h-12 w-12 sm:mr-2 sm:w-auto sm:px-4 sm:bg-transparent sm:rounded"
                            href="{{ action('PageController@help') }}" up-follow>

                            <i class="fas fa-question text-lg sm:hidden"></i>
                            <span class="hidden sm:inline">{{ trans('messages.help') }}</span>

                        </a>
                    </li>
                @endauth

                <!-- search-->
                @auth
                    <form class="form-inline my-2 hidden lg:block sm:px-4" role="search" action="{{ url('search') }}"
                        method="get">
                        <div class="input-group">
                            <input class="form-control form-control-sm" name="query" type="text"
                                value="{{ request()->get('query') }}" aria-label="Search"
                                placeholder="{{ trans('messages.search') }}...">

                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary btn-sm" type="submit"><span
                                        class="fa fa-search"></span></button>
                            </div>
                        </div>
                    </form>
                @endauth

                <!-- Notifications -->
                @auth
                    @if (isset($notifications))
                        <div class="dropdown hidden lg:block sm:px-4">
                            <a class="text-gray-200 px-1 flex flex-col justify-center items-center rounded-full  hover:bg-gray-600 bg-gray-700 h-12 w-12 sm:mr-2 sm:px-4 sm:bg-transparent sm:rounded"
                                data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fas fa-bell"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right rounded shadow">
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
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            <span>
                                <i class="fa fa-globe"></i>
                                {{ strtoupper(app()->getLocale()) }}
                                <i class="fa fa-caret-down"></i>
                                <span>
                        </a>
                        <div class="dropdown-menu">
                            @foreach (\Config::get('app.locales') as $locale)
                                @if ($locale !== app()->getLocale())
                                    <a class="dropdown-item"
                                        href="{{ Request::url() }}?force_locale={{ $locale }}"
                                        up-target="body">
                                        {{ strtoupper($locale) }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                @auth
                    <!-- User profile -->
                    <div class="nav-item dropdown">
                        <a data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                            <img class="rounded-full h-12 w-12"
                                src="{{ route('users.cover', [Auth::user(), 'small']) }}" />

                        </a>

                        <div class="dropdown-menu dropdown-menu-right rounded shadow" role="menu">
                            <a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}" up-target="body"><i
                                    class="fa fa-btn fa-user"></i> {{ trans('messages.profile') }}</a>
                            <a class="dropdown-item" href="{{ route('users.edit', Auth::user()) }}" up-target="body"><i
                                    class="fas fa-btn fa-user-edit"></i> {{ trans('messages.edit_my_profile') }}</a>

                            <a class="dropdown-item" href="{{ url('/logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="fa fa-btn fa-sign-out"></i> {{ trans('messages.logout') }}
                            </a>

                            <form id="logout-form" style="display: none;" action="{{ url('/logout') }}" method="POST">
                                @csrf
                                @honeypot
                            </form>

                            <!-- Admin -->
                            @if (Auth::user()->isAdmin())
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Admin</h6>

                                <a class="dropdown-item" href="{{ url('/admin/settings') }}" up-target="body">
                                    <i class="fa fa-cog"></i> Settings
                                </a>

                                <a class="dropdown-item" href="{{ url('/admin/user') }}">
                                    <i class="fa fa-users"></i> Users
                                </a>

                                <a class="dropdown-item" href="{{ url('/admin/groupadmins') }}">
                                    <i class="fa fa-users"></i> Group admins
                                </a>

                                <a class="dropdown-item" href="{{ url('/admin/undo') }}">
                                    <i class="fa fa-trash"></i> Recover content
                                </a>

                                <a class="dropdown-item" href="{{ action('Admin\InsightsController@index') }}">
                                    <i class="fa fa-line-chart"></i> {{ trans('messages.insights') }}
                                </a>

                                <a class="dropdown-item" href="{{ url('/admin/logs') }}">
                                    <i class="fa fa-keyboard-o"></i> Logs
                                </a>
                            @endif

                        </div>

                    </div>

                @endauth

                @guest

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('login') }}" up-modal=".dialog">
                            {{ trans('messages.login') }}
                        </a>
                    </li>

                    @can('create', App\User::class)
                        <li class="nav-item">
                            <a class="nav-link"  href="{{ url('register') }}" up-modal=".dialog">
                                {{ trans('messages.register') }}
                            </a>
                        </li>
                    @endcan

                @endguest

            </ul>

        </div>
    </div>

</nav>
