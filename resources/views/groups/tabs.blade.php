<div class="page_header">
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

        <li role="presentation" @if (isset($tab) && ($tab == 'home')) class="active" @endif>
            <a href="{{ route('groups.show', $group->id) }}">
                <i class="fa fa-home"></i> <span class="hidden-xs hidden-sm">{{ trans('messages.group_home') }}</span>
            </a>
        </li>


        @can ('viewDiscussions', $group)
            <li role="presentation" @if (isset($tab) && ($tab == 'discussion')) class="active" @endif>
                <a href="{{ route('groups.discussions.index', $group->id) }}">
                    <i class="fa fa-comments"></i> <span class="hidden-xs hidden-sm">{{ trans('messages.discussions') }}</span>
                </a>
            </li>
        @endcan

        @can ('viewActions', $group)
            <li role="presentation" @if (isset($tab) && ($tab == 'action')) class="active" @endif>
                <a href="{{ route('groups.actions.index', $group->id) }}">
                    <i class="fa fa-calendar"></i> <span class="hidden-xs hidden-sm">{{ trans('messages.agenda') }}</span>
                </a>
            </li>
        @endcan

        @can ('viewFiles', $group)
            <li role="presentation" @if (isset($tab) && ($tab == 'files')) class="active" @endif>
                <a href="{{ route('groups.files.index', $group->id) }}">
                    <i class="fa fa-files-o"></i> <span class="hidden-xs hidden-sm">{{ trans('messages.files') }}</span>
                </a>
            </li>
        @endcan

        @can ('viewMembers', $group)
            <li role="presentation" @if (isset($tab) && ($tab == 'users')) class="active" @endif>
                <a href="{{ route('groups.users.index', $group->id) }}">
                    <i class="fa fa-users"></i> <span class="hidden-xs hidden-sm">{{ trans('messages.members') }}</span>
                </a>
            </li>
        @endcan


        @can ('viewMembers', $group)
            <li role="presentation" @if (isset($tab) && ($tab == 'map')) class="active" @endif>
                <a href="{{ action('MapController@map', $group->id) }}">
                    <i class="fa fa-map-marker"></i> <span class="hidden-xs hidden-sm">{{ trans('messages.map') }}</span>
                </a>
            </li>
        @endcan

        @if ($group->isMember())
            <li role="presentation" @if (isset($tab) && ($tab == 'preferences')) class="active" @endif>
                <a href="{{ action('MembershipController@edit', $group->id) }}">
                    <i class="fa fa-bell-o"></i> <span class="hidden-xs hidden-sm">{{ trans('messages.settings') }}</span>
                </a>
            </li>
        @else
            @can ('join', $group)
                <li role="presentation" @if (isset($tab) && ($tab == 'preferences')) class="active" @endif>
                    <a href="{{ action('MembershipController@create', $group->id) }}">
                        <i class="fa fa-sign-in"></i> <span class="hidden-xs hidden-sm">{{ trans('messages.join') }}</span>
                    </a>
                </li>
            @else
                <li role="presentation" @if (isset($tab) && ($tab == 'preferences')) class="active" @endif>
                    <a href="{{ action('MembershipController@howToJoin', $group->id) }}">
                        <i class="fa fa-lock"></i> <span class="hidden-xs hidden-sm">{{ trans('messages.join') }}</span>
                    </a>
                </li>
            @endcan
        @endif


        @can ('administer', $group)
            <li role="presentation" @if (isset($tab) && ($tab == 'admin')) class="active" @endif>

            </li>



            <li role="presentation" class="dropdown @if (isset($tab) && ($tab == 'admin')) active @endif">
                <a href="#" class="dropdown-toggle" id="admin" data-toggle="dropdown" aria-controls="admin-contents" aria-expanded="false">
                    <i class="fa fa-wrench"></i> <span class="hidden-xs hidden-sm">{{ trans('messages.administration') }}</span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="admin" id="admin-contents">
                    <li>
                        <a href="{{ route('groups.edit', $group->id) }}">
                            <i class="fa fa-pencil"></i> {{ trans('messages.edit') }}
                        </a>
                    </li>

                    <li>
                        <a href="{{ action('InsightsController@forGroup', $group->id) }}">
                            <i class="fa fa-line-chart"></i> {{ trans('messages.insights') }}
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('groups.deleteconfirm', [$group->id]) }}">
                            <i class="fa fa-trash"></i> {{trans('messages.delete')}}
                        </a>
                    </li>

                </ul>
            </li>

        @endcan


    </ul>










@else

    <ul class="nav nav-tabs nav-groups">

        <li role="presentation" @if (isset($tab) && ($tab == 'home')) class="active" @endif>
            <a href="{{ route('groups.show', $group->id) }}">
                <i class="fa fa-home"></i> <span class="hidden-xs hidden-sm">{{ trans('messages.group_home') }}</span>
            </a>
        </li>


        @if ($group->isPublic() )
            <li role="presentation" @if (isset($tab) && ($tab == 'discussion')) class="active" @endif>
                <a href="{{ route('groups.discussions.index', $group->id) }}">
                    <i class="fa fa-comments"></i> <span class="hidden-xs hidden-sm">{{ trans('messages.discussions') }}</span>
                </a>
            </li>
        @endif

        @if ($group->isPublic() )
            <li role="presentation" @if (isset($tab) && ($tab == 'action')) class="active" @endif>
                <a href="{{ route('groups.actions.index', $group->id) }}">
                    <i class="fa fa-calendar"></i> <span class="hidden-xs hidden-sm">{{ trans('messages.agenda') }}</span>
                </a>
            </li>
        @endif

        @if ($group->isPublic() )
            <li role="presentation" @if (isset($tab) && ($tab == 'files')) class="active" @endif>
                <a href="{{ route('groups.files.index', $group->id) }}">
                    <i class="fa fa-files-o"></i> <span class="hidden-xs hidden-sm">{{ trans('messages.files') }}</span>
                </a>
            </li>
        @endif

        @if ($group->isPublic() )
            <li role="presentation" @if (isset($tab) && ($tab == 'users')) class="active" @endif>
                <a href="{{ route('groups.users.index', $group->id) }}">
                    <i class="fa fa-users"></i> <span class="hidden-xs hidden-sm">{{ trans('messages.members') }}</span>
                </a>
            </li>
        @endif


        @if ($group->isPublic() )
            <li role="presentation" @if (isset($tab) && ($tab == 'settings')) class="active" @endif>
                <a href="{{ action('MembershipController@create', $group->id) }}">
                    <i class="fa fa-cog"></i> <span class="hidden-xs hidden-sm">{{ trans('messages.join') }}</span>
                </a>
            </li>
        @endif
    </ul>


@endif
