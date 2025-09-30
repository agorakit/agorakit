<div class="mb-4">

    @include('partials.invite')

    <ul class="nav nav-underline">

        <li class="nav-item">
            <a class="nav-link @if (isset($tab) && $tab == 'home') active @endif" href="{{ route('groups.show', $group) }}">
                <i class="fa fa-info-circle me-2"></i>
                <span class="d-none d-sm-inline">{{ trans('messages.group_home') }}</span>
            </a>
        </li>

        @if ($group->getSetting('module_discussion', true) == true)
            @can('viewDiscussions', $group)
                <li class="nav-item">
                    <a class="nav-link @if (isset($tab) && $tab == 'discussions') active @endif" href="{{ route('groups.discussions.index', $group) }}">
                        <i class="fa fa-comments me-2"></i>
                        <span class="d-none d-sm-inline">{{ trans('messages.discussions') }}</span>
                    </a>
                </li>
            @endcan
        @endif

        @if ($group->getSetting('module_calendarevent', true) == true)
            @can('viewCalendarEvents', $group)
                <li class="nav-item">
                    <a class="nav-link @if (isset($tab) && $tab == 'calendarevents') active @endif" href="{{ route('groups.calendarevents.index', $group) }}">
                        <i class="fa fa-calendar me-2"></i>
                        <span class="d-none d-sm-inline">{{ trans('messages.calendar') }}</span>
                    </a>
                </li>
            @endcan
        @endif

        @if ($group->getSetting('module_file', true) == true)
            @can('viewFiles', $group)
                <li class="nav-item">
                    <a class="nav-link @if (isset($tab) && $tab == 'files') active @endif" href="{{ route('groups.files.index', $group) }}">
                        <i class="fa fa-files-o  me-2"></i>
                        <span class="d-none d-sm-inline">{{ trans('messages.files') }}</span>
                    </a>
                </li>
            @endcan
        @endif

        @if ($group->getSetting('module_member', true) == true)
            @can('viewMembers', $group)
                <li class="nav-item">
                    <a class="nav-link @if (isset($tab) && $tab == 'users') active @endif" href="{{ route('groups.users.index', $group) }}">
                        <i class="fa fa-users me-2"></i>
                        <span class="d-none d-sm-inline">{{ trans('messages.members') }}</span>
                    </a>
                </li>
            @endcan
        @endif

        @if ($group->getSetting('module_map', true) == true)
            @can('viewMembers', $group)
                <li class="nav-item">
                    <a class="nav-link @if (isset($tab) && $tab == 'map') active @endif"
                        href="{{ action('GroupMapController@index', $group) }}">
                        <i class="fa fa-map-marker me-2"></i>
                        <span class="d-none d-sm-inline">{{ trans('messages.map') }}</span>
                    </a>
                </li>
            @endcan
        @endif

        @if ($group->getSetting('module_custom_name'))
            @if ($group->isMember())
                <li class="nav-item">
                    <a class="nav-link @if (isset($tab) && $tab == 'custom') active @endif"
                        href="{{ action('ModuleController@show', $group) }}">
                        <i class="fa {{ $group->getSetting('module_custom_icon') }} sm:hidden"></i class="hidden me-2">
                        <span class="d-none d-sm-inline">{{ $group->getSetting('module_custom_name') }}</span>
                    </a>
                </li>
            @endif
        @endif

        @if ($group->isMember())
            <li class="nav-item">
                <a class="nav-link @if (isset($tab) && $tab == 'preferences') active @endif"
                    href="{{ action('GroupMembershipController@edit', $group) }}">
                    <i class="fa fa-bell-o me-2"></i>
                    <span class="d-none d-sm-inline">{{ trans('messages.preferences') }}</span>
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link @if (isset($tab) && $tab == 'settings') active @endif"
                    href="{{ action('GroupMembershipController@create', $group) }}" up-layer="new">
                    <i class="fa fa-sign-in me-2"></i>
                    <span class="d-none d-sm-inline">{{ trans('messages.join') }}</span>
                </a>
            </li>
        @endif

        @can('administer', $group)
            <li class="nav-item dropdown">

                <a aria-controls="admin-contents" aria-expanded="false"
                    class=" nav-link dropdown-toggle  @if (isset($tab) && $tab == 'admin') active @endif" data-bs-toggle="dropdown" href="#"
                    id="admin">
                    <i class="fa fa-wrench me-2"></i> <span class="d-none d-sm-inline">{{ trans('messages.settings') }}</span>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('groups.edit', $group) }}">
                        <i class="fa fa-cogs me-2"></i> {{ trans('Configuration') }}
                    </a>

                    <a class="dropdown-item" href="{{ route('groups.tags.edit', $group) }}">
                        <i class="fa fa-tags me-2"></i> {{ trans('Tags') }}
                    </a>

                    <a class="dropdown-item" href="{{ action('ModuleController@update', $group) }}">
                        <i class="fa fa-toggle-on me-2"></i> {{ trans('messages.features') }}
                    </a>

                    <a class="dropdown-item" href="{{ action('GroupPermissionController@index', $group) }}">
                        <i class="fa fa-crown me-2"></i> {{ trans('Permissions') }}
                    </a>

                    <a class="dropdown-item" href="{{ action('GroupInsightsController@index', $group) }}">
                        <i class="fa fa-line-chart me-2"></i> {{ trans('messages.insights') }}
                    </a>

                    <a class="dropdown-item" href="{{ action('GroupController@export', $group) }}" up-follow="false">
                        <i class="fa fa-keyboard-o me-2"></i> {{ trans('messages.export_group') }}
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="{{ route('groups.deleteconfirm', [$group]) }}">
                        <i class="fa fa-trash me-2"></i> @lang('Delete group')
                    </a>
                </div>
            </li>
        @endcan

    </ul>
</div>
