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
                <i class="fa fa-child"></i> {{Config::get('mobilizator.name')}}
            </a>
        </div>
        <div class="navbar-collapse collapse" id="navbar">

            <ul class="nav navbar-nav">
                <li>
                    <a href="{{ action('DashboardController@agenda') }}">
                        {{trans('group.latest_actions')}}
                    </a>
                </li>

                @if ($user_logged)
                    <li>
                        <a href="{{ action('DashboardController@unreadDiscussions') }}">
                            {{ trans('messages.latest_discussions') }}
                            @if ($unread_discussions > 0) <span class="badge">{{$unread_discussions}}</span>@endif
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                {{ trans('messages.your_groups') }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                @forelse ($user_groups as $user_group)
                                    <li><a href="{{ action('GroupController@show', $user_group->id)}}">{{$user_group->name}}</a></li>
                                @empty
                                    <li><a href="{{ action('DashboardController@index')}}">{{ trans('membership.not_subscribed_to_group_yet') }}</a></li>
                                @endforelse
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ action('GroupController@create') }}">
                                    <i class="fa fa-bolt"></i> {{ trans('group.create_a_group_button') }}</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="{{ action('DashboardController@users') }}">
                                {{trans('messages.users_list')}}
                            </a>
                        </li>
                    @endif

                </ul>

                <ul class="nav navbar-nav navbar-right">
                    @if ($user_logged)

                        <form class="navbar-form navbar-left" role="search" action="{{url('search')}}">
                            <div class="input-group">
                                <input type="text" name="query" class="form-control" placeholder="{{trans('messages.search')}}...">
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
                                <li><a href="{{action('UserController@show', $user->id)}}"><i class="fa fa-btn fa-user"></i> {{ trans('messages.profile') }}</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i> {{ trans('messages.logout') }}</a></li>
                            </ul>
                        </li>
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
                                <?php
                                // OU dans le cas de domain url ;-)
                                /*foreach (\Config::get('app.locales') as $lang => $locale){
                                if($lang !== app()->getLocale()){
                                echo '<li><a href="'.str_replace(url('/'), $locale['url'], request()->fullUrl()).'">'.strtoupper($lang).'</a></li>';
                            }
                        }*/
                        ?>
                    </ul>
                </li>
            @endif
        </ul>
    </div><!--/.nav-collapse -->
</div><!--/.container-fluid -->
</nav>


@if (isset($user->verified) && ($user->verified == 0))
    <div class="container">
        <div class="alert alert-warning alert-dismissible fade in" id="message">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <i class="fa fa-info-circle"></i>
            {{trans('messages.email_not_verified')}}
            <br/>
            <a style="font-size: 0.6em" href="{{action('UserController@sendVerificationAgain', $user->id)}}">{{trans('messages.email_not_verified_send_again_verification')}}</a>
        </div>
    </div>
@endif
