<div class="group-header">
    <h1>
        <a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
        @if (isset($tab) && ($tab <> 'home'))
            <a href="{{ route('groups.show', $group->id) }}">{{ $group->name }}</a>
        @else
            {{ $group->name }}
        @endif

        <span class="small">
            @if ($group->isPublic())
                <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
            @else
                <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
            @endif
        </span>
    </h1>
</div>


@if (Auth::check())

    <ul class="nav nav-tabs nav-groups">

        <li class="nav-item">
            <a href="{{ route('groups.show', $group->id) }}" class="nav-link @if (isset($tab) && ($tab == 'home')) active @endif">
                <i class="fa fa-info-circle"></i> <span class="d-none d-lg-inline">{{ trans('messages.group_home') }}</span>
            </a>
        </li>


        @if ($group->getSetting('module_discussion', true) == true)
            @can ('viewDiscussions', $group)
                <li class="nav-item">
                    <a href="{{ route('groups.discussions.index', $group->id) }}"  class="nav-link @if (isset($tab) && ($tab == 'discussion')) active @endif">
                        <i class="fa fa-comments"></i> <span class="d-none d-lg-inline">{{ trans('messages.discussions') }}</span>
                    </a>
                </li>
            @endcan
        @endif

        @if ($group->getSetting('module_action', true) == true)
            @can ('viewActions', $group)
                <li class="nav-item">
                    <a href="{{ route('groups.actions.index', $group->id) }}"  class="nav-link @if (isset($tab) && ($tab == 'action')) active @endif">
                        <i class="fa fa-calendar"></i> <span class="d-none d-lg-inline">{{ trans('messages.agenda') }}</span>
                    </a>
                </li>
            @endcan
        @endif



        @if ($group->getSetting('module_file', true) == true)
            @can ('viewFiles', $group)
                <li class="nav-item">
                    <a href="{{ route('groups.files.index', $group->id) }}"  class="nav-link @if (isset($tab) && ($tab == 'files')) active @endif">
                        <i class="fa fa-files-o"></i> <span class="d-none d-lg-inline">{{ trans('messages.files') }}</span>
                    </a>
                </li>
            @endcan
        @endif

        @if ($group->getSetting('module_member', true) == true)
            @can ('viewMembers', $group)
                <li class="nav-item">
                    <a href="{{ route('groups.users.index', $group->id) }}"  class="nav-link @if (isset($tab) && ($tab == 'users')) active @endif">
                        <i class="fa fa-users"></i> <span class="d-none d-lg-inline">{{ trans('messages.members') }}</span>
                    </a>
                </li>
            @endcan

        @endif

        @if ($group->getSetting('module_map', true) == true)
            @can ('viewMembers', $group)
                <li class="nav-item">
                    <a href="{{ action('MapController@map', $group->id) }}"  class="nav-link @if (isset($tab) && ($tab == 'map')) active @endif">
                        <i class="fa fa-map-marker"></i> <span class="d-none d-lg-inline">{{ trans('messages.map') }}</span>
                    </a>
                </li>
            @endcan
        @endif

        @if ($group->getSetting('module_custom_name'))
            <li class="nav-item">
                <a href="{{ action('ModuleController@show', $group) }}"  class="nav-link @if (isset($tab) && ($tab == 'custom')) active @endif">
                    <i class="fa {{$group->getSetting('module_custom_icon')}}"></i> <span class="d-none d-lg-inline">{{$group->getSetting('module_custom_name')}}</span>
                </a>
            </li>
        @endif


        @if ($group->isMember())
            <li class="nav-item">
                <a href="{{ action('MembershipController@edit', $group->id) }}"  class="nav-link @if (isset($tab) && ($tab == 'preferences')) active @endif">
                    <i class="fa fa-bell-o"></i> <span class="d-none d-lg-inline">{{ trans('messages.settings') }}</span>
                </a>
            </li>
        @else
            <li class="nav-item">
                <a href="{{ action('MembershipController@create', $group->id) }}"  class="nav-link @if (isset($tab) && ($tab == 'settings')) active @endif">
                    <i class="fa fa-sign-in"></i> <span class="d-none d-lg-inline">{{ trans('messages.join') }}</span>
                </a>
            </li>
        @endif


        @can ('administer', $group)
            <li class="nav-item dropdown">
                <a href="#" id="admin" data-toggle="dropdown" aria-controls="admin-contents" aria-expanded="false"  class="dropdown-toggle nav-link @if (isset($tab) && ($tab == 'admin')) active @endif">
                    <i class="fa fa-wrench"></i> <span class="d-none d-lg-inline">{{ trans('messages.administration') }}</span>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('groups.edit', $group->id) }}">
                        <i class="fa fa-pencil"></i> {{ trans('messages.edit') }}
                    </a>

                    <a class="dropdown-item" href="{{ action('ModuleController@update', $group->id) }}">
                        <i class="fa fa-toggle-on"></i> {{ trans('messages.features') }}
                    </a>

                    <a class="dropdown-item" href="{{ action('InsightsController@forGroup', $group->id) }}">
                        <i class="fa fa-line-chart"></i> {{ trans('messages.insights') }}
                    </a>

                    <a class="dropdown-item" href="{{ route('groups.deleteconfirm', [$group->id]) }}">
                        <i class="fa fa-trash"></i> {{trans('messages.delete')}}
                    </a>
                </div>
            </li>

        @endcan


    </ul>










@else

    <ul class="nav nav-tabs nav-groups">

        <li class="nav-item" role="presentation" >
            <a class="nav-link @if (isset($tab) && ($tab == 'home')) active @endif" href="{{ route('groups.show', $group->id) }}">
                <i class="fa fa-info-circle"></i> <span class="d-none d-lg-inline">{{ trans('messages.group_home') }}</span>
            </a>
        </li>


        @if ($group->getSetting('module_discussion', true) == true)
            @if ($group->isPublic() )
                <li class="nav-item" role="presentation">
                    <a class="nav-link @if (isset($tab) && ($tab == 'discussion')) active @endif" href="{{ route('groups.discussions.index', $group->id) }}">
                        <i class="fa fa-comments"></i> <span class="d-none d-lg-inline">{{ trans('messages.discussions') }}</span>
                    </a>
                </li>
            @endif
        @endif

        @if ($group->getSetting('module_action', true) == true)
            @if ($group->isPublic() )
                <li class="nav-item" role="presentation">
                    <a class="nav-link @if (isset($tab) && ($tab == 'action')) active @endif" href="{{ route('groups.actions.index', $group->id) }}">
                        <i class="fa fa-calendar"></i> <span class="d-none d-lg-inline">{{ trans('messages.agenda') }}</span>
                    </a>
                </li>
            @endif


        @endif

        @if ($group->getSetting('module_files', true) == true)
            @if ($group->isPublic() )
                <li class="nav-item" role="presentation">
                    <a class="nav-link @if (isset($tab) && ($tab == 'files')) active @endif" href="{{ route('groups.files.index', $group->id) }}">
                        <i class="fa fa-files-o"></i> <span class="d-none d-lg-inline">{{ trans('messages.files') }}</span>
                    </a>
                </li>
            @endif

        @endif

        @if ($group->getSetting('module_member', true) == true)
            @if ($group->isPublic() )
                <li class="nav-item" role="presentation">
                    <a class="nav-link @if (isset($tab) && ($tab == 'users')) active @endif" href="{{ route('groups.users.index', $group->id) }}">
                        <i class="fa fa-users"></i> <span class="d-none d-lg-inline">{{ trans('messages.members') }}</span>
                    </a>
                </li>
            @endif
        @endif


        @if ($group->isPublic() )
            <li class="nav-item" role="presentation">
                <a class="nav-link @if (isset($tab) && ($tab == 'settings')) active @endif" href="{{ action('MembershipController@create', $group->id) }}">
                    <i class="fa fa-cog"></i> <span class="d-none d-lg-inline">{{ trans('messages.join') }}</span>
                </a>
            </li>
        @endif
    </ul>


@endif
