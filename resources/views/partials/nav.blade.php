<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container-fluid">
        <!-- logo -->
        <a class="navbar-brand me-3" href="{{ route('index') }}">
            @if (Storage::exists('public/logo/favicon.png'))
                <img src="{{ asset('storage/logo/favicon.png') }}" width="30" height="24" />
            @else
                <img src="/images/logo-white.svg" width="30" height="24" />
            @endif
            <span class="">{{ setting('name') }}</span>
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbar" type="button" aria-controls="navbar" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav">

                @auth
                    @if (Auth::user()->groups()->count() > 0)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                {{ trans('messages.my_groups') }}
                            </a>
                            <div class="dropdown-menu">
                                @foreach (Auth::user()->groups()->orderBy('name')->get() as $group)
                                    <a class="dropdown-item" href="{{ route('groups.show', $group) }}" up-target="body">{{ $group->name }}</a>
                                @endforeach
                            </div>
                        </li>
                    @endif
                @endauth

                <!-- Overview -->
                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
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
                                data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                            {{ strtoupper(app()->getLocale()) }}
                        </a>

                        <ul class="dropdown-menu">
                            @foreach (\Config::get('app.locales') as $locale)
                                @if ($locale !== app()->getLocale())
                                    <li>
                                        <a class="dropdown-item" href="{{ Request::url() }}?force_locale={{ $locale }}" up-target="body">
                                            {{ strtoupper($locale) }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif

                <!-- search-->
                @auth
                    <li class="nav-item">
                        <form class="d-flex" role="search" action="{{ url('search') }}" method="get">
                            <div class="input-group">
                                <input class="form-control" name="query" type="text" value="{{ request()->get('query') }}" aria-label="{{ trans('messages.search') }}"
                                    placeholder="{{ trans('messages.search') }}">
                                <input class="btn btn-outline-secondary" type="submit" value="search" />
                            </div>
                        </form>
                    </li>
                @endauth

                <!-- User profile -->
                @auth
                    <div class="nav-item dropdown ms-3">
                        <a data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                            <img class="avatar rounded" src="{{ route('users.cover', [Auth::user(), 'small']) }}" />
                        </a>

                        <div class="dropdown-menu" role="menu">
                            <a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}" up-target="body"><i class="fa fa-btn fa-user"></i>
                                {{ trans('messages.profile') }}</a>
                            <a class="dropdown-item" href="{{ route('users.edit', Auth::user()) }}" up-target="body"><i class="fas fa-btn fa-user-edit"></i>
                                {{ trans('messages.edit_my_profile') }}</a>

                            <a class="dropdown-item" href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
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
        </div>
    </div>

</nav>
