<nav class="navbar navbar-expand-lg navbar-light bg-light">

    <a class="navbar-brand" href="{{ route('index') }}">
        <i class="fa fa-child"></i> <span class="hidden-xs hidden-sm hidden-md">{{Config::get('agorakit.name')}}</span>
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#agorakit_navbar" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>



    <div class="collapse navbar-collapse" id="agorakit_navbar">

        <div class="navbar-nav mr-auto">

            <!-- Overview -->
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    {{ trans('messages.overview') }}
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ action('DashboardController@groups') }}">
                        <i class="fa fa-cubes"></i> {{trans('messages.groups')}}
                    </a>

                    <a class="dropdown-item" href="{{ action('DashboardController@discussions') }}">
                        <i class="fa fa-comments-o"></i> {{trans('messages.discussions')}}
                    </a>

                    <a class="dropdown-item" href="{{ action('DashboardController@agenda') }}">
                        <i class="fa fa-calendar"></i> {{trans('messages.agenda')}}
                    </a>


                    @if (Auth::check())
                        <a class="dropdown-item" href="{{ action('DashboardController@files') }}">
                            <i class="fa fa-files-o"></i> {{trans('messages.files')}}
                        </a>

                        <a class="dropdown-item" href="{{ action('DashboardController@users') }}">
                            <i class="fa fa-users"></i> {{trans('messages.users_list')}}
                        </a>
                    @endif


                    <a class="dropdown-item" href="{{ action('DashboardController@map') }}">
                        <i class="fa fa-map-marker"></i> {{trans('messages.map')}}
                    </a>
                </div>
            </div>



            <!-- Groups -->
            @if (Auth::check())
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        {{ trans('messages.your_groups') }}
                    </a>
                    <div class="dropdown-menu">
                        @forelse (Auth::user()->groups()->orderBy('name')->get() as $group)
                            <a class="dropdown-item" href="{{ route('groups.show', $group->id)}}">{{$group->name}}</a>
                        @empty
                            <a class="dropdown-item" href="{{ route('index')}}">{{ trans('membership.not_subscribed_to_group_yet') }}</a>
                        @endforelse

                        <div class="dropdown-divider"></div>


                        <a class="dropdown-item" class="dropdown-item" href="{{ action('DashboardController@groups') }}">
                            {{trans('messages.all_groups')}}
                        </a>


                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="{{ route('groups.create') }}">
                            <i class="fa fa-bolt"></i> {{ trans('group.create_a_group_button') }}
                        </a>


                    </div>
                </div>
            @endif




            @if (Auth::check())
                <!-- User profile -->
                <div class="dropdown nav-item">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <span class="avatar"><img src="{{Auth::user()->avatar()}}" class="rounded-circle" style="width:24px; height:24px"/></span> {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu" role="menu">
                        <a class="dropdown-item" href="{{route('users.show', Auth::user()->id)}}"><i class="fa fa-btn fa-user"></i> {{ trans('messages.profile') }}</a>
                        <a class="dropdown-item" href="{{route('users.edit', Auth::user()->id)}}"><i class="fa fa-btn fa-edit"></i> {{ trans('messages.edit_my_profile') }}</a>

                        <a class="dropdown-item" href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="fa fa-btn fa-sign-out"></i> {{ trans('messages.logout') }}
                        </a>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>


                        <!-- Admin -->
                        @if (Auth::user()->isAdmin())
                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="{{ url('/admin/settings') }}">
                                <i class="fa fa-cog"></i> Settings
                            </a>



                            <a class="dropdown-item" href="{{ url('/admin/user') }}">
                                <i class="fa fa-users"></i> Users
                            </a>

                            <a class="dropdown-item" href="{{ action('InsightsController@forAllGroups') }}">
                                <i class="fa fa-line-chart"></i> {{ trans('messages.insights') }}
                            </a>

                            <a class="dropdown-item" href="{{ url('/admin/logs') }}">
                                <i class="fa fa-keyboard-o"></i> Logs
                            </a>
                        @endif

                    </div>

                </div>


            @else
                <div class="nav-item">
                    <a class="nav-link" href="{{ url('register') }}">{{ trans('messages.register') }}</a>
                </div>

                <div class="nav-item">
                    <a class="nav-link" href="{{ url('login') }}">{{ trans('messages.login') }}</a>
                </div>
            @endif
        </div>




        @if (Auth::check())
            <!-- Search -->

            <form class="form-inline my-2 my-lg-0" role="search" action="{{url('search')}}">
                <input class="form-control form-control-sm mr-sm-2" type="text" name="query"  placeholder="{{trans('messages.search')}}..." aria-label="Search">
                <button class="btn btn-outline-success btn-sm my-2 my-sm-0" type="submit"><span class="glyphicon glyphicon-search" aria-hidden="true"></span>{{trans('messages.search')}}</button>
            </form>
        @endif

        <div class="navbar-nav ml-auto">
            @if(\Config::has('app.locales'))
                <div class="dropdown nav-item">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-language"></i> {{ strtoupper(app()->getLocale()) }}
                    </a>
                    <div class="dropdown-menu">
                        @foreach(\Config::get('app.locales') as $locale)
                            @if($locale !== app()->getLocale())
                                <a class="dropdown-item" href="{{Request::url()}}?force_locale={{$locale}}">
                                    {{ strtoupper($locale) }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>


    </nav>
