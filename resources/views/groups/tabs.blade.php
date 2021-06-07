<h1 class="text-lg font-bold mb-8 text-gray-700 truncate">
    <a up-follow up-reveal="false" up-cache="false" href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i
        class="fa fa-angle-right"></i>
    @if (isset($tab) && ($tab <> 'home'))
        <a up-follow up-reveal="false" href="{{ route('groups.show', $group) }}">{{ $group->name }}</a>
        @else
        {{ $group->name }}
        @endif


        @if ($group->isOpen())
        <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
        @elseif ($group->isClosed())
        <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
        @else
        <i class="fa fa-eye-slash" title="{{trans('group.secret')}}"></i>
        @endif
</h1>

@include('partials.invite')


<div class="flex justify-start content-start mb-10 space-x-5 lg:space-x-10 text-gray-700 tabs flex-wrap">

    @if (Auth::check())


    <a up-follow up-reveal="false" href="{{ route('groups.show', $group) }}"
        class="@if (isset($tab) && ($tab == 'home')) font-bold text-blue-800 border-b-4 border-blue-800 @endif hover:text-blue-900">
        <i class="fa fa-info-circle sm:hidden"></i> <span class="hidden sm:inline">{{ trans('messages.group_home') }}</span>
    </a>


    @if ($group->getSetting('module_discussion', true) == true)
    @can ('viewDiscussions', $group)

    <a up-follow up-reveal="false" up-cache="false" href="{{ route('groups.discussions.index', $group) }}"
        class="@if (isset($tab) && ($tab == 'discussion')) font-bold text-blue-800 border-b-4 border-blue-800 @endif hover:text-blue-900">
        <i class="fa fa-comments sm:hidden"></i> <span class="hidden sm:inline">{{ trans('messages.discussions') }}</span>
    </a>

    @endcan
    @endif

    @if ($group->getSetting('module_action', true) == true)
    @can ('viewActions', $group)

    <a up-follow up-reveal="false" href="{{ route('groups.actions.index', $group) }}"
    class="@if (isset($tab) && ($tab == 'action')) font-bold text-blue-800 border-b-4 border-blue-800 @endif hover:text-blue-900">
    <i class="fa fa-calendar sm:hidden"></i> <span class="hidden sm:inline">{{ trans('messages.agenda') }}</span>
    </a>

    @endcan
    @endif



    @if ($group->getSetting('module_file', true) == true)
    @can ('viewFiles', $group)

    <a up-follow up-reveal="false" href="{{ route('groups.files.index', $group) }}"
        class=" @if (isset($tab) && ($tab == 'files')) font-bold text-blue-800 border-b-4 border-blue-800 @endif hover:text-blue-900">
        <i class="fa fa-files-o sm:hidden"></i> <span class="hidden sm:inline">{{ trans('messages.files') }}</span>
    </a>

    @endcan
    @endif

    @if ($group->getSetting('module_member', true) == true)
    @can ('viewMembers', $group)

    <a up-follow up-reveal="false" href="{{ route('groups.users.index', $group) }}"
        class=" @if (isset($tab) && ($tab == 'users')) font-bold text-blue-800 border-b-4 border-blue-800 @endif hover:text-blue-900">
        <i class="fa fa-users sm:hidden"></i> <span class="hidden sm:inline">{{ trans('messages.members') }}</span>
    </a>

    @endcan

    @endif

    @if ($group->getSetting('module_map', true) == true)
    @can ('viewMembers', $group)

    <a href="{{ action('GroupMapController@index', $group) }}"
        class=" @if (isset($tab) && ($tab == 'map')) font-bold text-blue-800 border-b-4 border-blue-800 @endif hover:text-blue-900">
        <i class="fa fa-map-marker sm:hidden"></i> <span class="hidden sm:inline">{{ trans('messages.map') }}</span>
    </a>

    @endcan
    @endif

    @if ($group->getSetting('module_custom_name'))
    @if ($group->isMember())

    <a href="{{ action('ModuleController@show', $group) }}"
        class=" @if (isset($tab) && ($tab == 'custom')) font-bold text-blue-800 border-b-4 border-blue-800 @endif hover:text-blue-900">
        <i class="fa {{$group->getSetting('module_custom_icon')}} sm:hidden"></i> <span
            class="hidden sm:inline">{{$group->getSetting('module_custom_name')}}</span>
    </a>

    @endif
    @endif


    @if ($group->isMember())

    <a up-follow up-reveal="false" href="{{ action('GroupMembershipController@edit', $group) }}"
        class=" @if (isset($tab) && ($tab == 'preferences')) font-bold text-blue-800 border-b-4 border-blue-800 @endif hover:text-blue-900">
        <i class="fa fa-bell-o sm:hidden"></i> <span class="hidden sm:inline">{{ trans('messages.settings') }}</span>
    </a>

    @else

    <a up-follow up-reveal="false" href="{{ action('GroupMembershipController@create', $group) }}"
        class=" @if (isset($tab) && ($tab == 'settings')) font-bold text-blue-800 border-b-4 border-blue-800 @endif hover:text-blue-900">
        <i class="fa fa-sign-in sm:hidden"></i> <span class="hidden sm:inline">{{ trans('messages.join') }}</span>
    </a>

    @endif


    @can ('administer', $group)
    <div class="dropdown">
        <a href="#" id="admin" data-toggle="dropdown" aria-controls="admin-contents" aria-expanded="false"
            class="dropdown-toggle  @if (isset($tab) && ($tab == 'admin')) font-bold text-blue-800 border-b-4 border-blue-800 @endif hover:text-blue-900">
            <i class="fa fa-wrench sm:hidden"></i> <span class="hidden sm:inline">@lang('Administer')</span>
        </a>
        <div class="dropdown-menu">
            <a up-follow up-reveal="false" class="dropdown-item" href="{{ route('groups.edit', $group) }}">
                <i class="fa fa-cogs"></i> {{ trans('Configuration') }}
            </a>

            <a up-follow up-reveal="false" class="dropdown-item" href="{{ route('groups.tags.edit', $group) }}">
                <i class="fa fa-tags"></i> {{ trans('Tags') }}
            </a>

            <a up-follow up-reveal="false" class="dropdown-item" href="{{ action('ModuleController@update', $group) }}">
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
    </div>

    @endcan




    @else


    <a class=" @if (isset($tab) && ($tab == 'home')) text-blue-800 border-b-4 border-blue-800 @endif"
        href="{{ route('groups.show', $group) }}">
        <i class="fa fa-info-circle"></i> <span class="d-none d-lg-inline">{{ trans('messages.group_home') }}</span>
    </a>



    @if ($group->getSetting('module_discussion', true) == true)
    @if ($group->isOpen() )

    <a up-follow up-reveal="false"
        class=" @if (isset($tab) && ($tab == 'discussion')) text-blue-800 border-b-4 border-blue-800 @endif"
        href="{{ route('groups.discussions.index', $group) }}">
        <i class="fa fa-comments"></i> <span class="d-none d-lg-inline">{{ trans('messages.discussions') }}</span>
    </a>

    @endif
    @endif

    @if ($group->getSetting('module_action', true) == true)
    @if ($group->isOpen() )

    <a class=" @if (isset($tab) && ($tab == 'action')) text-blue-800 border-b-4 border-blue-800 @endif"
        href="{{ route('groups.actions.index', $group) }}">
        <i class="fa fa-calendar"></i> <span class="d-none d-lg-inline">{{ trans('messages.agenda') }}</span>
    </a>

    @endif


    @endif

    @if ($group->getSetting('module_files', true) == true)
    @if ($group->isOpen() )

    <a up-follow up-reveal="false"
        class=" @if (isset($tab) && ($tab == 'files')) text-blue-800 border-b-4 border-blue-800 @endif"
        href="{{ route('groups.files.index', $group) }}">
        <i class="fa fa-files-o"></i> <span class="d-none d-lg-inline">{{ trans('messages.files') }}</span>
    </a>

    @endif

    @endif

    @if ($group->getSetting('module_member', true) == true)
    @if ($group->isOpen() )

    <a up-follow up-reveal="false"
        class=" @if (isset($tab) && ($tab == 'users')) text-blue-800 border-b-4 border-blue-800 @endif"
        href="{{ route('groups.users.index', $group) }}">
        <i class="fa fa-users"></i> <span class="d-none d-lg-inline">{{ trans('messages.members') }}</span>
    </a>

    @endif
    @endif


    @if ($group->isOpen() )

    <a up-follow up-reveal="false"
        class=" @if (isset($tab) && ($tab == 'settings')) text-blue-800 border-b-4 border-blue-800 @endif"
        href="{{ action('GroupMembershipController@create', $group) }}">
        <i class="fa fa-cog"></i> <span class="d-none d-lg-inline">{{ trans('messages.join') }}</span>
    </a>

    @endif



    @endif


</div>