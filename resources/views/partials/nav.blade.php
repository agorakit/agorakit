<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                <span class="sr-only">{{ trans('messages.toggle_navigation') }}</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="{{ action('DashboardController@index') }}" class="navbar-brand">
                <i class="fa fa-child"></i> <span class="hidden-xs hidden-sm hidden-md">{{Config::get('agorakit.name')}}</span>
            </a>
        </div>
        <div class="navbar-collapse collapse" id="navbar">

            <ul class="nav navbar-nav">

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        {{ trans('messages.overview') }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ action('DashboardController@groups') }}">
                                <i class="fa fa-cubes"></i> {{trans('messages.groups')}}
                            </a>
                        </li>

                        <li>
                            <a href="{{ action('DashboardController@discussions') }}">
                                <i class="fa fa-comments-o"></i> {{trans('messages.discussions')}}
                            </a>
                        </li>

                        <li>
                            <a href="{{ action('DashboardController@agenda') }}">
                                <i class="fa fa-calendar"></i> {{trans('messages.agenda')}}
                            </a>
                        </li>

                        @if (Auth::check())
                            <li>
                                <a href="{{ action('DashboardController@files') }}">
                                    <i class="fa fa-files-o"></i> {{trans('messages.files')}}
                                </a>
                            </li>

                            <li>
                                <a href="{{ action('DashboardController@users') }}">
                                    <i class="fa fa-users"></i> {{trans('messages.users_list')}}
                                </a>
                            </li>
                        @endif

                        <li>
                            <a href="{{ action('DashboardController@map') }}">
                                <i class="fa fa-map-marker"></i> {{trans('messages.map')}}
                            </a>
                        </li>


                    </ul>
                </li>


                @if (Auth::check())
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ trans('messages.your_groups') }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            @forelse (Auth::user()->groups()->orderBy('name')->get() as $group)
                                <li><a href="{{ action('GroupController@show', $group->id)}}">{{$group->name}}</a></li>
                            @empty
                                <li><a href="{{ action('DashboardController@index')}}">{{ trans('membership.not_subscribed_to_group_yet') }}</a></li>
                            @endforelse
                            <li role="separator" class="divider"></li>

                            <li>
                                <a href="{{ action('DashboardController@groups') }}">
                                    {{trans('messages.all_groups')}}
                                </a>
                            </li>

                            <li role="separator" class="divider"></li>

                            <li><a href="{{ action('GroupController@create') }}">
                                <i class="fa fa-bolt"></i> {{ trans('group.create_a_group_button') }}</a>
                            </li>

                        </ul>
                    </li>


                @endif

            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if (Auth::check())

                    <form class="navbar-form navbar-left" role="search" action="{{url('search')}}">
                        <div class="input-group">
                            <input type="text" name="query" class="form-control" placeholder="{{trans('messages.search')}}..." style="font-size:10px; width: auto">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-default" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                            </span>
                        </div><!-- /input-group -->
                    </form>


                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="avatar"><img src="{{Auth::user()->avatar()}}" class="img-circle" style="width:24px; height:24px"/></span> {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{action('UserController@show', Auth::user()->id)}}"><i class="fa fa-btn fa-user"></i> {{ trans('messages.profile') }}</a></li>
                            <li><a href="{{action('UserController@edit', Auth::user()->id)}}"><i class="fa fa-btn fa-edit"></i> {{ trans('messages.edit_my_profile') }}</a></li>
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i> {{ trans('messages.logout') }}</a></li>
                        </ul>
                    </li>

                    @if (Auth::user()->isAdmin())
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                Admin <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/admin/user') }}">Users</a></li>
                                <li>
                                    <a href="{{ action('InsightsController@forAllGroups') }}">
                                        <i class="fa fa-line-chart"></i> {{ trans('messages.insights') }}
                                    </a>
                                </li>
                                <li><a href="{{ url('/translations') }}">Translations</a></li>
                                <li><a href="{{ url('/admin/logs') }}">Logs</a></li>

                            </ul>
                        </li>
                    @endif

                @else
                    <li><a href="{{ url('register') }}">{{ trans('messages.register') }}</a></li>
                    <li><a href="{{ url('login') }}">{{ trans('messages.login') }}</a></li>
                @endif

                @if(\Config::has('app.locales'))
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ strtoupper(app()->getLocale()) }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            @foreach(\Config::get('app.locales') as $lang => $locale)
                                @if($lang !== app()->getLocale())
                                    <li>
                                        <a href="<?= count($_GET) ? '?'.http_build_query(array_merge($_GET, ['force_locale' => $lang])) : '?force_locale='.$lang ?>">
                                            <?= strtoupper($lang); ?>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>


@if (isset(Auth::user()->verified) && (Auth::user()->verified == 0))
    <div class="container">
        <div class="alert alert-warning alert-dismissible fade in" id="message">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <i class="fa fa-info-circle"></i>
            {{trans('messages.email_not_verified')}}
            <br/>
            <a href="{{action('UserController@sendVerificationAgain', Auth::user()->id)}}">{{trans('messages.email_not_verified_send_again_verification')}}</a>
        </div>
    </div>
@endif
