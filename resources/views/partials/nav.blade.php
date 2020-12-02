<nav class="bg-gray-800 flex text-gray-200 items-center justify-between sm:justify-start px-5 py-3">

    <a up-follow up-cache="false" href="{{ route('index') }}"
        class="hidden sm:flex text-gray-200 px-1 justify-center items-center rounded h-12 w-12 sm:w-auto sm:px-4">
        @if(Storage::exists('public/logo/favicon.png'))
        <img src="{{asset('storage/logo/favicon.png')}}" width="40" height="40" />
        @else
        <img src="/images/logo-white.svg" width="40" height="40" />
        @endif
        <span class="ml-1 hidden sm:inline text-gray-200">{{ setting('name') }}</span>
    </a>

    <a up-follow up-cache="false" href="{{ route('index') }}"
    class="sm:hidden text-gray-200 px-1 flex flex-col justify-center items-center rounded-full  hover:bg-gray-600 bg-gray-700 h-12 w-12 sm:mr-2 sm:w-auto sm:px-4 sm:bg-transparent sm:rounded"
    >
    <i class="fa fa-home text-lg"></i>
    </a>



    @auth
    <div class="dropdown">
        <a href="#"
            class="text-gray-200 px-1 flex flex-col justify-center items-center rounded-full  hover:bg-gray-600 bg-gray-700 h-12 w-12 sm:mr-2 sm:w-auto sm:px-4 sm:bg-transparent sm:rounded"
            data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">

            <i class="fa fa-cubes text-lg sm:hidden"></i>
            <span class="hidden sm:inline">
                {{ trans('messages.groups') }}
                <i class="fa fa-caret-down"></i>
            </span>

        </a>
        <div class="dropdown-menu rounded shadow">

            <h6 class="dropdown-header">{{ trans('messages.my_groups') }}</h6>

            {{--
            <a up-target="body" class="dropdown-item" class="dropdown-item"
                href="{{ action('GroupController@indexOfMyGroups') }}">
                {{ trans('messages.my_groups') }}
            </a>
            <div class="dropdown-divider"></div>
            --}}

            




            @forelse (Auth::user()->groups()->orderBy('name')->get() as $group)
            <a up-target="body" class="dropdown-item" href="{{ route('groups.show', $group) }}">{{ $group->name }}</a>
            @empty
            <a class="dropdown-item"
                href="{{ route('index') }}">{{ trans('membership.not_subscribed_to_group_yet') }}</a>
            @endforelse

            <div class="dropdown-divider"></div>

            <a up-target="body" class="dropdown-item" href="{{ route('groups.create') }}">
                {{ trans('group.create_a_group_button') }}
            </a>
        </div>
    </div>
    @endauth


    <!-- Overview -->
    <div class="dropdown text-gray-200">
        <a href="#"
            class="text-gray-200 px-1 flex flex-col justify-center items-center rounded-full  hover:bg-gray-600 bg-gray-700 h-12 w-12 sm:mr-2 sm:w-auto sm:px-4 sm:bg-transparent sm:rounded"
            data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">


            <i class="fa fa-university text-lg sm:hidden"></i>
            <span class="hidden sm:inline">
            @lang('Overview')
            <i class="fa fa-caret-down"></i>
            </span>


        </a>
        <div class="dropdown-menu rounded shadow">

            <h6 class="dropdown-header">@lang('Overview')</h6>

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
    @auth
    <div class="nav-item sm:px-4">
        <a up-follow
            class="text-gray-200 px-1 flex flex-col justify-center items-center rounded-full  hover:bg-gray-600 bg-gray-700 h-12 w-12 sm:mr-2 sm:w-auto sm:px-4 sm:bg-transparent sm:rounded"
            href="{{ action('PageController@help') }}">

            <i class="fas fa-question text-lg sm:hidden"></i>
            <span class="hidden sm:inline">{{ trans('messages.help') }}</span>



        </a>
    </div>
    @endauth


    <div class="sm:flex-grow"></div>




  
    <!-- search-->
    @auth
    <form class="form-inline my-2 hidden lg:block sm:px-4" role="search" action="{{ url('search') }}"
    method="get">
    <div class="input-group">
        <input class="form-control form-control-sm" type="text" name="query"
            placeholder="{{ trans('messages.search') }}..." aria-label="Search">
         @csrf
        
        <div class="input-group-append">
            <button class="btn btn-outline-secondary btn-sm" type="submit"><span class="fa fa-search"></span></button>
        </div>
    </div>
    </form>
    @endauth


    <!-- Notifications -->
    @auth
    @if(isset($notifications))
    <div class="dropdown hidden lg:block sm:px-4">
        <a href="#" 
        class="text-gray-200 px-1 flex flex-col justify-center items-center rounded-full  hover:bg-gray-600 bg-gray-700 h-12 w-12 sm:mr-2 sm:px-4 sm:bg-transparent sm:rounded"
        data-toggle="dropdown" role="button"
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
    @endauth




    <!-- locales -->
    @if(\Config::has('app.locales'))
    <div class="dropdown sm:px-4 hidden lg:block">
        <a href="#" 
        class="text-gray-200 px-1 flex flex-col justify-center items-center rounded-full  hover:bg-gray-600 bg-gray-700 h-12 w-12 mr-2 sm:w-auto sm:px- sm:bg-transparent sm:rounded"
        data-toggle="dropdown" role="button" aria-haspopup="true"
            aria-expanded="false">
            <span>
            <i class="fa fa-globe"></i> 
            {{ strtoupper(app()->getLocale()) }}
            <i class="fa fa-caret-down"></i>
            <span>
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
    <div class="dropdown flex-shrink-0 h-12 w-12">
        <a href="#" 
        data-toggle="dropdown" role="button" aria-expanded="false">
            <img src="{{ route('users.cover', [Auth::user(), 'small']) }}" class="rounded-full h-12 w-12" />

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

    <a up-modal=".dialog"
        class="text-gray-200 flex flex-col justify-center items-center rounded hover:bg-gray-600 bg-gray-700 h-12 w-auto sm:w-auto px-4 my-2 mr-2"
        href="{{ url('login') }}">

        
        <span class="text-xs sm:text-base">{{ trans('messages.login') }}</span>
    </a>

    <a up-modal=".dialog"
        class="text-gray-200 flex flex-col justify-center items-center rounded hover:bg-gray-600 bg-gray-700 h-12 w-auto sm:w-auto px-4 my-2"
        href="{{ url('register') }}">
        
        <span class="text-xs sm:text-base">{{ trans('messages.register') }}</span>
    </a>

    @endguest

</nav>