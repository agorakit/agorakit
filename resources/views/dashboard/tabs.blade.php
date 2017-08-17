<ul class="nav nav-pills">

    <li @if (isset($tab) && ($tab == 'homepage')) class="active" @endif>
        <a href="{{ action('DashboardController@index') }}">
            <i class="fa fa-home"></i> <span class="hidden-xs">{{ trans('messages.presentation') }}</span>
        </a>
    </li>


    <li @if (isset($tab) && ($tab == 'groups')) class="active" @endif>
        <a href="{{ action('DashboardController@groups') }}">
            <i class="fa fa-cubes"></i> <span class="hidden-xs">{{ trans('messages.groups') }}</span>
        </a>
    </li>


    <li @if (isset($tab) && ($tab == 'discussions')) class="active" @endif>
        <a href="{{ action('DashboardController@discussions') }}">
            <i class="fa fa-comments"></i> <span class="hidden-xs">{{ trans('messages.latest_discussions') }}</span>
        </a>
    </li>




    <li @if (isset($tab) && ($tab == 'actions')) class="active" @endif>
        <a href="{{ action('DashboardController@agenda') }}">
            <i class="fa fa-calendar"></i> <span class="hidden-xs">{{ trans('messages.agenda') }}</span>
        </a>
    </li>


    @if (Auth::check())
        <li @if (isset($tab) && ($tab == 'files')) class="active" @endif>
            <a href="{{ action('DashboardController@files') }}">
                <i class="fa fa-file-o"></i> <span class="hidden-xs">{{ trans('messages.files') }}</span>
            </a>
        </li>
    @endif


    @if (Auth::check())
        <li @if (isset($tab) && ($tab == 'users')) class="active" @endif>
            <a href="{{ action('DashboardController@users') }}">
                <i class="fa fa-users"></i> <span class="hidden-xs">{{ trans('messages.members') }}</span>
            </a>
        </li>
    @endif


    <li @if (isset($tab) && ($tab == 'map')) class="active" @endif>
        <a href="{{ action('DashboardController@map') }}">
            <i class="fa fa-map-marker"></i> <span class="hidden-xs">{{ trans('messages.map') }}</span>
        </a>
    </li>

</ul>

<div class="spacer"></div>
