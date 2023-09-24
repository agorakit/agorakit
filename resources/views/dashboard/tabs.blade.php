<ul class="nav nav-pills nav-fill mb-5 mt-2">
    <li class="nav-item">
        <a href="{{ route('index') }}" class="nav-link @if (isset($tab) && $tab == 'homepage') active @endif">
            <i class="fa fa-home"></i> <span
                class="ms-2 d-none d-lg-inline">{{ trans('messages.presentation') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ action('GroupController@index') }}" class="nav-link @if (isset($tab) && $tab == 'groups') active @endif">
            <i class="fa fa-cubes"></i> <span class="ms-2 d-none d-lg-inline ">{{ trans('messages.groups') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ action('DiscussionController@index') }}"
            class="nav-link @if (isset($tab) && $tab == 'discussions') active @endif">
            <i class="fa fa-comments"></i> <span
                class="ms-2 d-none d-lg-inline ">{{ trans('messages.latest_discussions') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ action('ActionController@index') }}"
            class="nav-link @if (isset($tab) && $tab == 'actions') active @endif">
            <i class="fa fa-calendar"></i> <span
                class="ms-2 d-none d-lg-inline ">{{ trans('messages.agenda') }}</span>
        </a>
    </li>

    @auth
        <li class="nav-item">
            <a href="{{ action('FileController@index') }}" class="nav-link @if (isset($tab) && $tab == 'files') active @endif">
                <i class="fa fa-files-o"></i> <span class="ms-2 d-none d-lg-inline ">{{ trans('messages.files') }}</span>
            </a>
        </li>
    @endauth

    @auth
        <li class="nav-item">
            <a href="{{ action('UserController@index') }}"
                class="nav-link @if (isset($tab) && $tab == 'users') active @endif">
                <i class="fa fa-users"></i> <span
                    class="ms-2 d-none d-lg-inline ">{{ trans('messages.members') }}</span>
            </a>
        </li>
    @endauth

    <li class="nav-item">
        <a href="{{ action('MapController@index') }}" class="nav-link @if (isset($tab) && $tab == 'map') active @endif">
            <i class="fa fa-map-marker"></i> <span
                class="ms-2 d-none d-lg-inline ">{{ trans('messages.map') }}</span>
        </a>
    </li>

</ul>
