<nav class="bg-gray-700 flex text-gray-200 items-center justify-between px-5 py-1">

    <a up-follow up-cache="false" class="navbar-brand flex items-center" href="{{ route('index') }}">
        @if(Storage::exists('public/logo/favicon.png'))
        <img src="{{asset('storage/logo/favicon.png')}}" width="40" height="40" />
        @else
        <img src="/images/logo-white.svg" width="40" height="40" />
        @endif
        <span class="ml-1 hidden lg:block  text-gray-200">{{ setting('name') }}</span>
    </a>

    <!-- Mobile navigation drawer toggler : -->
    <div class="lg:hidden">
        <div class="nav-item">
            <a href="{{ route('navigation.main') }}" up-history="false" up-drawer=".navigation-container"
                class="text-gray-200">
                <i class="fas fa-bars"></i>
                Menu
            </a>
        </div>
    </div>


    <!-- Classic navigation shown on larger screens : -->
    <div class="hidden lg:flex items-center">

        @auth
        <div class="dropdown">
            <a href="#" class="text-gray-200 dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                aria-expanded="false">
                <i class="fa fa-cubes"></i> {{ trans('messages.groups') }}
            </a>
            <div class="dropdown-menu rounded shadow">

                <a up-target="body" class="dropdown-item" class="dropdown-item"
                    href="{{ action('GroupController@indexOfMyGroups') }}">
                    {{ trans('messages.my_groups') }}
                </a>

                <div class="dropdown-divider"></div>




                @forelse (Auth::user()->groups()->orderBy('name')->get() as $group)
                <a up-target="body" class="dropdown-item"
                    href="{{ route('groups.show', $group) }}">{{ $group->name }}</a>
                @empty
                <a class="dropdown-item"
                    href="{{ route('index') }}">{{ trans('membership.not_subscribed_to_group_yet') }}</a>
                @endforelse

                <div class="dropdown-divider"></div>

                <a up-target="body" class="dropdown-item" href="{{ route('groups.create') }}">
                    <i class="fa fa-plus-circle"></i> {{ trans('group.create_a_group_button') }}
                </a>
            </div>
        </div>
        @endauth


        <!-- Overview -->
        <div class="dropdown text-gray-200">
            <a href="#" class="text-gray-200 nav-link dropdown-toggle" data-toggle="dropdown" role="button"
                aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-asterisk"></i> @lang('Overview')
            </a>
            <div class="dropdown-menu rounded shadow">


                <a up-target="body" class="dropdown-item" class="dropdown-item"
                    href="{{ action('GroupController@index') }}">
                    <i class="fa fa-layer-group"></i> {{ trans('messages.all_groups') }}
                </a>

                <a up-target="body" up-cache="false" class="dropdown-item"
                    href="{{ action('DiscussionController@index') }}">
                    <i class="fa fa-comments-o"></i> {{ trans('messages.discussions') }}
                </a>

                <a up-target="body" class="dropdown-item" href="{{ action('ActionController@index') }}">
                    <i class="fa fa-calendar"></i> {{ trans('messages.agenda') }}
                </a>

                <a up-target="body" class="dropdown-item" href="{{ action('TagController@index') }}">
                    <i class="fa fa-tag"></i> @lang('Tags')
                </a>

                <a class="dropdown-item" href="{{ action('MapController@index') }}">
                    <i class="fa fa-map-marker"></i> {{ trans('messages.map') }}
                </a>
                <a up-target="body" class="dropdown-item" href="{{ action('FileController@index') }}">
                    <i class="fa fa-files-o"></i> {{ trans('messages.files') }}
                </a>

                <a up-target="body" class="dropdown-item" href="{{ action('UserController@index') }}">
                    <i class="fa fa-users"></i> {{ trans('messages.users_list') }}
                </a>

            </div>
        </div>

        <!-- help -->
        <div class="nav-item">
            <a class="nav-link text-gray-200" href="{{ action('PageController@help') }}">
                <i class="fa fa-info-circle"></i>
                {{ trans('messages.help') }}
            </a>
        </div>







    <!-- search-->
    @auth
    <form up-target="body" class="form-inline my-2 hidden xl:block" role="search" action="{{ url('search') }}"
        method="get">
        <div class="input-group">
            <input class="form-control form-control-sm" type="text" name="query"
                placeholder="{{ trans('messages.search') }}..." aria-label="Search">

            <div class="input-group-append">
                <button class="btn btn-outline-secondary btn-sm" type="submit"><span
                        class="fa fa-search"></span></button>
            </div>
        </div>
    </form>
    @endauth


    <!-- Notifications -->
    @if(isset($notifications))
    <div class="dropdown nav-item">
        <a href="#" class="text-gray-200 nav-link dropdown-toggle" data-toggle="dropdown" role="button"
            aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-bell"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right rounded shadow">
            @foreach($notifications as $notification)

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


    <!-- locales -->
    @if(\Config::has('app.locales'))
    <div class="dropdown nav-item">
        <a href="#" class="text-gray-200 nav-link dropdown-toggle" data-toggle="dropdown" role="button"
            aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-language"></i> {{ strtoupper(app()->getLocale()) }}
        </a>
        <div class="dropdown-menu dropdown-menu-right rounded shadow">
            @foreach(\Config::get('app.locales') as $locale)
            @if($locale !== app()->getLocale())
            <a up-target="body" class="dropdown-item" href="{{ Request::url() }}?force_locale={{ $locale }}">
                {{ strtoupper($locale) }}
            </a>
            @endif
            @endforeach
        </div>
    </div>
    @endif



    @auth
    <!-- User profile -->
    <div class="dropdown ">
        <a href="#" class="text-gray-200 dropdown-toggle flex items-center" data-toggle="dropdown" role="button"
            aria-expanded="false">
            <img src="{{ route('users.cover', [Auth::user(), 'small']) }}" class="rounded-full h-8 w-8 mr-2" /></span>
            {{ Auth::user()->name }} <span class="caret"></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right rounded shadow" role="menu">
            <a up-target="body" class="dropdown-item" href="{{ route('users.show', Auth::user()) }}"><i
                    class="fa fa-btn fa-user"></i> {{ trans('messages.profile') }}</a>
            <a up-target="body" class="dropdown-item" href="{{ route('users.edit', Auth::user()) }}"><i
                    class="fa fa-btn fa-edit"></i> {{ trans('messages.edit_my_profile') }}</a>

            <a class="dropdown-item" href="{{ url('/logout') }}"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="fa fa-btn fa-sign-out"></i> {{ trans('messages.logout') }}
            </a>

            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                @csrf
                @honeypot
            </form>


            <!-- Admin -->
            @if(Auth::user()->isAdmin())
            <div class="dropdown-divider"></div>
            <h6 class="dropdown-header">Admin</h6>

            <a up-target="body" class="dropdown-item" href="{{ url('/admin/settings') }}">
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
    <div class="nav-item">
        <div class="btn-group">
            <a up-modal=".dialog" class="btn btn-primary" href="{{ url('login') }}">{{ trans('messages.login') }}</a>
            <a up-modal=".dialog" class="btn btn-secondary"
                href="{{ url('register') }}">{{ trans('messages.register') }}</a>
        </div>
    </div>
    @endguest


</nav>