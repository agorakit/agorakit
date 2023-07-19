<h1 class="truncate">
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

@include('partials.invite')

<ul class="nav nav-tabs">

    <li class="nav-item">
        <a class="nav-link @if (isset($tab) && $tab == 'home') active @endif" href="{{ route('groups.show', $group) }}" up-follow up-reveal="false">
            <i class="fa fa-info-circle"></i> <span class="hidden sm:inline">{{ trans('messages.group_home') }}</span>
        </a>
    </li>

    @if ($group->getSetting('module_discussion', true) == true)
        @can('viewDiscussions', $group)
            <li class="nav-item">
                <a class="nav-link @if (isset($tab) && $tab == 'discussion') active @endif" href="{{ route('groups.discussions.index', $group) }}" up-follow up-reveal="false" up-cache="false">
                    <i class="fa fa-comments"></i> {{ trans('messages.discussions') }}
                </a>
            </li>
        @endcan
    @endif

    @if ($group->getSetting('module_action', true) == true)
        @can('viewActions', $group)
            <li class="nav-item">
                <a class="nav-link @if (isset($tab) && $tab == 'action') active @endif" href="{{ route('groups.actions.index', $group) }}" up-follow up-reveal="false">
                    <i class="fa fa-calendar"></i> {{ trans('messages.agenda') }}
                </a>
            </li>
        @endcan
    @endif

    @if ($group->getSetting('module_file', true) == true)
        @can('viewFiles', $group)
            <li class="nav-item">
                <a class="nav-link @if (isset($tab) && $tab == 'files') active @endif" href="{{ route('groups.files.index', $group) }}" up-follow up-reveal="false">
                    <i class="fa fa-files-o sm:hidden"></i> <span class="hidden sm:inline">{{ trans('messages.files') }}</span>
                </a>
            </li>
        @endcan
    @endif

    @if ($group->getSetting('module_member', true) == true)
        @can('viewMembers', $group)
            <li class="nav-item">
                <a class="nav-link @if (isset($tab) && $tab == 'users') active @endif" href="{{ route('groups.users.index', $group) }}" up-follow up-reveal="false">
                    <i class="fa fa-users sm:hidden"></i> <span class="hidden sm:inline">{{ trans('messages.members') }}</span>
                </a>
            </li>
        @endcan
    @endif

    @if ($group->getSetting('module_map', true) == true)
        @can('viewMembers', $group)
            <li class="nav-item">
                <a class="nav-link @if (isset($tab) && $tab == 'map') active @endif" href="{{ action('GroupMapController@index', $group) }}">
                    <i class="fa fa-map-marker sm:hidden"></i> <span class="hidden sm:inline">{{ trans('messages.map') }}</span>
                </a>
            </li>
        @endcan
    @endif

    @if ($group->getSetting('module_custom_name'))
        @if ($group->isMember())
            <li class="nav-item">
                <a class="nav-link @if (isset($tab) && $tab == 'custom') active @endif" href="{{ action('ModuleController@show', $group) }}">
                    <i class="fa {{ $group->getSetting('module_custom_icon') }} sm:hidden"></i> <span
                        class="hidden sm:inline">{{ $group->getSetting('module_custom_name') }}</span>
                </a>
            </li>
        @endif
    @endif

    @if ($group->isMember())
        <li class="nav-item">
            <a class="nav-link @if (isset($tab) && $tab == 'preferences') active @endif" href="{{ action('GroupMembershipController@edit', $group) }}" up-follow up-reveal="false">
                <i class="fa fa-bell-o sm:hidden"></i> <span class="hidden sm:inline">{{ trans('messages.settings') }}</span>
            </a>
        </li>
    @else
        <li class="nav-item">
            <a class="nav-link @if (isset($tab) && $tab == 'settings') active @endif" href="{{ action('GroupMembershipController@create', $group) }}" up-follow up-reveal="false">
                <i class="fa fa-sign-in sm:hidden"></i> <span class="hidden sm:inline">{{ trans('messages.join') }}</span>
            </a>
        </li>
    @endif

    @can('administer', $group)
        <li class="nav-item dropdown">

            <a class=" nav-link dropdown-toggle  @if (isset($tab) && $tab == 'admin') active @endif" id="admin" data-toggle="dropdown" href="#" aria-controls="admin-contents"
                aria-expanded="false">
                <i class="fa fa-wrench sm:hidden"></i> <span class="hidden sm:inline">@lang('Administer')</span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('groups.edit', $group) }}" up-follow up-reveal="false">
                    <i class="fa fa-cogs"></i> {{ trans('Configuration') }}
                </a>

                <a class="dropdown-item" href="{{ route('groups.tags.edit', $group) }}" up-follow up-reveal="false">
                    <i class="fa fa-tags"></i> {{ trans('Tags') }}
                </a>

                <a class="dropdown-item" href="{{ action('ModuleController@update', $group) }}" up-follow up-reveal="false">
                    <i class="fa fa-toggle-on"></i> {{ trans('messages.features') }}
                </a>

                <a class="dropdown-item" href="{{ action('GroupPermissionController@index', $group) }}">
                    <i class="fa fa-crown"></i> {{ trans('Permissions') }}
                </a>

                <a class="dropdown-item" href="{{ action('GroupInsightsController@index', $group) }}">
                    <i class="fa fa-line-chart"></i> {{ trans('messages.insights') }}
                </a>

                <a class="dropdown-item" href="{{ route('groups.deleteconfirm', [$group]) }}">
                    <i class="fa fa-trash"></i> @lang('Delete group')
                </a>
            </div>
        </li>
    @endcan

</ul>
