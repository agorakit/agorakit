<!--

<ul class="nav nav-pills dashboard-nav">
    <li class="nav-item">
        <a href="{{ route('index') }}" class="nav-link @if (isset($tab) && ($tab == 'homepage')) active @endif">
            <i class="fa fa-home"></i> <span class="hidden-xs">{{ trans('messages.presentation') }}</span>
        </a>
    </li>


    <li class="nav-item">
        <a href="{{ action('DashboardController@groups') }}" class="nav-link @if (isset($tab) && ($tab == 'groups')) active @endif">
            <i class="fa fa-cubes"></i> <span class="hidden-xs">{{ trans('messages.groups') }}</span>
        </a>
    </li>


    <li class="nav-item">
        <a href="{{ action('DashboardController@discussions') }}" class="nav-link @if (isset($tab) && ($tab == 'discussions')) active @endif">
            <i class="fa fa-comments"></i> <span class="hidden-xs">{{ trans('messages.latest_discussions') }}</span>
        </a>
    </li>




    <li class="nav-item">
        <a href="{{ action('DashboardController@agenda') }}" class="nav-link @if (isset($tab) && ($tab == 'actions')) active @endif">
            <i class="fa fa-calendar"></i> <span class="hidden-xs">{{ trans('messages.agenda') }}</span>
        </a>
    </li>


    @if (Auth::check())
        <li class="nav-item">
            <a href="{{ action('DashboardController@files') }}" class="nav-link @if (isset($tab) && ($tab == 'files')) active @endif">
                <i class="fa fa-files-o"></i> <span class="hidden-xs">{{ trans('messages.files') }}</span>
            </a>
        </li>
    @endif


    @if (Auth::check())
        <li class="nav-item">
            <a href="{{ action('DashboardController@users') }}" class="nav-link @if (isset($tab) && ($tab == 'users')) active @endif">
                <i class="fa fa-users"></i> <span class="hidden-xs">{{ trans('messages.members') }}</span>
            </a>
        </li>
    @endif


    <li class="nav-item">
        <a href="{{ action('MapController@index') }}" class="nav-link @if (isset($tab) && ($tab == 'map')) active @endif">
            <i class="fa fa-map-marker"></i> <span class="hidden-xs">{{ trans('messages.map') }}</span>
        </a>
    </li>

</ul>

-->
