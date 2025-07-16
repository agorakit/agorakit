<ul class="nav nav-underline mb-5 mt-2 gap-5">
    <li class="nav-item">
        <a class="nav-link @if (isset($tab) && $tab == 'homepage') active @endif" href="{{ route('presentation') }}">
            <i class="fas fa-info-circle"></i> <span class="ms-2 d-none d-lg-inline">{{ trans('messages.presentation') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link @if (isset($tab) && $tab == 'groups') active @endif" href="{{ action('GroupController@index') }}">
            <i class="fa fa-cubes"></i> <span class="ms-2 d-none d-lg-inline ">{{ trans('messages.groups') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link @if (isset($tab) && $tab == 'discussions') active @endif" href="{{ route('discussions') }}">
            <i class="fa fa-comments"></i> <span class="ms-2 d-none d-lg-inline ">{{ trans('messages.latest_discussions') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link @if (isset($tab) && $tab == 'calendarevents') active @endif" href="{{ action('CalendarEventController@index') }}">
            <i class="fa fa-calendar"></i> <span class="ms-2 d-none d-lg-inline ">{{ trans('messages.agenda') }}</span>
        </a>
    </li>

    @auth
        <li class="nav-item">
            <a class="nav-link @if (isset($tab) && $tab == 'files') active @endif" href="{{ action('FileController@index') }}">
                <i class="fa fa-files-o"></i> <span class="ms-2 d-none d-lg-inline ">{{ trans('messages.files') }}</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link @if (isset($tab) && $tab == 'users') active @endif" href="{{ action('UserController@index') }}">
                <i class="fa fa-users"></i> <span class="ms-2 d-none d-lg-inline ">{{ trans('messages.members') }}</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link @if (isset($tab) && $tab == 'map') active @endif" href="{{ action('MapController@index') }}">
                <i class="fa fa-map-marker"></i> <span class="ms-2 d-none d-lg-inline ">{{ trans('messages.map') }}</span>
            </a>
        </li>
    @endauth

</ul>
