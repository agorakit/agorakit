<div class="mb-4">

    @include('partials.invite')

    <h1 class="text-truncate mb-md-5 mb-2">
        <a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
        @if (isset($tab) && $tab != 'home')
            <a href="{{ route('groups.show', $group) }}">{{ $group->name }}</a>
        @else
            {{ $group->name }}
        @endif

        @if ($group->isOpen())
            <i class="fa fa-globe" title="{{ trans('group.open') }}"></i>
        @elseif ($group->isClosed())
            <i class="fa fa-lock" title="{{ trans('group.closed') }}"></i>
        @else
            <i class="fa fa-eye-slash" title="{{ trans('group.secret') }}"></i>
        @endif
    </h1>

    <ul class="nav nav-tabs">

        <li class="nav-item">
            <a class="nav-link @if (isset($tab) && $tab == 'home') active @endif" href="{{ route('groups.show', $group) }}"  up-reveal="false">
                <i class="fa fa-info-circle me-2"></i> {{ trans('messages.group_home') }}
            </a>
        </li>

        @if ($group->getSetting('module_discussion', true) == true)
            @can('viewDiscussions', $group)
                <li class="nav-item">
                    <a class="nav-link @if (isset($tab) && $tab == 'discussion') active @endif" href="{{ route('groups.discussions.index', $group) }}"  up-reveal="false"
                        up-cache="false">
                        <i class="fa fa-comments me-2"></i> {{ trans('messages.discussions') }}
                    </a>
                </li>
            @endcan
        @endif

        @if ($group->getSetting('module_action', true) == true)
            @can('viewActions', $group)
                <li class="nav-item">
                    <a class="nav-link @if (isset($tab) && $tab == 'action') active @endif" href="{{ route('groups.actions.index', $group) }}"  up-reveal="false">
                        <i class="fa fa-calendar me-2"></i> {{ trans('messages.agenda') }}
                    </a>
                </li>
            @endcan
        @endif

        @if ($group->getSetting('module_file', true) == true)
            @can('viewFiles', $group)
                <li class="nav-item">
                    <a class="nav-link @if (isset($tab) && $tab == 'files') active @endif" href="{{ route('groups.files.index', $group) }}"  up-reveal="false">
                        <i class="fa fa-files-o  me-2"></i> {{ trans('messages.files') }}
                    </a>
                </li>
            @endcan
        @endif

        @if ($group->getSetting('module_member', true) == true)
            @can('viewMembers', $group)
                <li class="nav-item">
                    <a class="nav-link @if (isset($tab) && $tab == 'users') active @endif" href="{{ route('groups.users.index', $group) }}"  up-reveal="false">
                        <i class="fa fa-users me-2"></i> {{ trans('messages.members') }}
                    </a>
                </li>
            @endcan
        @endif

        @if ($group->getSetting('module_map', true) == true)
            @can('viewMembers', $group)
                <li class="nav-item">
                    <a class="nav-link @if (isset($tab) && $tab == 'map') active @endif" href="{{ action('GroupMapController@index', $group) }}">
                        <i class="fa fa-map-marker me-2"></i> {{ trans('messages.map') }}
                    </a>
                </li>
            @endcan
        @endif

        @if ($group->getSetting('module_custom_name'))
            @if ($group->isMember())
                <li class="nav-item">
                    <a class="nav-link @if (isset($tab) && $tab == 'custom') active @endif" href="{{ action('ModuleController@show', $group) }}">
                        <i class="fa {{ $group->getSetting('module_custom_icon') }} sm:hidden"></i class="hidden me-2">{{ $group->getSetting('module_custom_name') }}
                    </a>
                </li>
            @endif
        @endif

        @if ($group->isMember())
            <li class="nav-item">
                <a class="nav-link @if (isset($tab) && $tab == 'preferences') active @endif" href="{{ action('GroupMembershipController@edit', $group) }}" >
                    <i class="fa fa-bell-o me-2"></i> {{ trans('messages.settings') }}
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link @if (isset($tab) && $tab == 'settings') active @endif" href="{{ action('GroupMembershipController@create', $group) }}" up-layer="new">
                    <i class="fa fa-sign-in me-2"></i> {{ trans('messages.join') }}
                </a>
            </li>
        @endif

        @can('administer', $group)
            <li class="nav-item dropdown">

                <a class=" nav-link dropdown-toggle  @if (isset($tab) && $tab == 'admin') active @endif" id="admin" data-bs-toggle="dropdown" href="#"
                    aria-controls="admin-contents" aria-expanded="false">
                    <i class="fa fa-wrench me-2"></i> @lang('Administer')
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('groups.edit', $group) }}">
                        <i class="fa fa-cogs me-2"></i> {{ trans('Configuration') }}
                    </a>

                    <a class="dropdown-item" href="{{ route('groups.tags.edit', $group) }}">
                        <i class="fa fa-tags me-2"></i> {{ trans('Tags') }}
                    </a>

                    <a class="dropdown-item" href="{{ action('ModuleController@update', $group) }}"  up-reveal="false">
                        <i class="fa fa-toggle-on me-2"></i> {{ trans('messages.features') }}
                    </a>

                    <a class="dropdown-item" href="{{ action('GroupPermissionController@index', $group) }}">
                        <i class="fa fa-crown me-2"></i> {{ trans('Permissions') }}
                    </a>

                    <a class="dropdown-item" href="{{ action('GroupInsightsController@index', $group) }}">
                        <i class="fa fa-line-chart me-2"></i> {{ trans('messages.insights') }}
                    </a>

                    <a class="dropdown-item" href="{{ route('groups.deleteconfirm', [$group]) }}">
                        <i class="fa fa-trash me-2"></i> @lang('Delete group')
                    </a>
                </div>
            </li>
        @endcan

    </ul>
</div>
