@extends('dialog')
@section('content')

    <div class="navigation-container">

        <div class="navigation ">

            <a up-target="body" class="item" href="{{route('users.show', Auth::user())}}">
                <span class="avatar mr-2"><img src="{{route('users.cover', [Auth::user(), 'small'])}}" class="rounded-circle" style="width:32px; height:32px"/></span> {{ Auth::user()->name }}
            </a>


        </div>


        <!-- search-->
        <form up-target="body" class="form-inline my-2" role="search" action="{{url('search')}}" method="get">
            <div class="input-group">
                <input class="form-control" type="text" name="query"  placeholder="{{trans('messages.search')}}..." aria-label="Search">

                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit"><span class="fa fa-search"></span></button>
                </div>
            </div>
        </form>


        <div class="navigation">




            <a up-target="body" class="title" class="item" href="{{ action('GroupController@indexOfMyGroups') }}">
                {{trans('messages.my_groups')}}
            </a>




            @forelse (Auth::user()->groups()->orderBy('name')->get() as $group)

                <a up-target="body"  class="item" href="{{ route('groups.show', $group)}}">{{$group->name}}</a>

            @empty
                <a href="{{ route('index')}}"  class="item" >{{ trans('membership.not_subscribed_to_group_yet') }}</a>
            @endforelse

            <div class="divider"></div>

            <a up-target="body"  class="item text-secondary"  href="{{ route('groups.create') }}">
                <i class="fa fa-plus-circle"></i> {{ trans('group.create_a_group_button') }}
            </a>



            <div class="title mt-3">
                @lang('Overview')
            </div>

            <a up-target="body" class="item" class="dropdown-item" href="{{ action('GroupController@index') }}">
                <i class="fa fa-layer-group"></i> {{trans('messages.all_groups')}}
            </a>

            <a up-target="body" up-cache="false" class="item" href="{{ action('DiscussionController@index') }}">
                <i class="fa fa-comments-o"></i> {{trans('messages.discussions')}}
            </a>

            <a up-target="body" class="item" href="{{ action('ActionController@index') }}">
                <i class="fa fa-calendar"></i> {{trans('messages.agenda')}}
            </a>

            <a up-target="body" class="item" href="{{ action('TagController@index') }}">
                <i class="fa fa-tag"></i> @lang('Tags')
            </a>

            <a class="item" href="{{ action('MapController@index') }}">
                <i class="fa fa-map-marker"></i> {{trans('messages.map')}}
            </a>
            <a up-target="body" class="item" href="{{ action('FileController@index') }}">
                <i class="fa fa-files-o"></i> {{trans('messages.files')}}
            </a>

            <a up-target="body" class="item" href="{{ action('UserController@index') }}">
                <i class="fa fa-users"></i> {{trans('messages.users_list')}}
            </a>



            <a class="item" href="{{ action('PageController@help') }}">
                <i class="fa fa-info-circle"></i>
                {{trans('messages.help')}}
            </a>


            <!-- Admin -->
            @if (Auth::user()->isAdmin())

                <div class="title mt-3">
                    Admin
                </div>

                <a up-target="body" class="item" href="{{ url('/admin/settings') }}">
                    <i class="fa fa-cog"></i> Settings
                </a>

                <a class="item" href="{{ url('/admin/user') }}">
                    <i class="fa fa-users"></i> Users
                </a>

                <a class="item" href="{{ url('/admin/groupadmins') }}">
                    <i class="fa fa-users"></i> Group admins
                </a>

                <a class="item" href="{{ url('/admin/undo') }}">
                    <i class="fa fa-trash"></i> Recover content
                </a>

                <a class="item" href="{{ action('Admin\InsightsController@index') }}">
                    <i class="fa fa-line-chart"></i> {{ trans('messages.insights') }}
                </a>

                <a class="item" href="{{ url('/admin/logs') }}">
                    <i class="fa fa-keyboard-o"></i> Logs
                </a>
            @endif

            <div class="title mt-3">My account</div>
            <a up-target="body" class="item" href="{{route('users.show', Auth::user())}}"><i class="fa fa-btn fa-user"></i> {{ trans('messages.profile') }}</a>
            <a up-target="body" class="item" href="{{route('users.edit', Auth::user())}}"><i class="fa fa-btn fa-edit"></i> {{ trans('messages.edit_my_profile') }}</a>

            <a class="item" href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="fa fa-btn fa-sign-out"></i> {{ trans('messages.logout') }}
            </a>

            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                @csrf
                @honeypot
            </form>


        </div>




    </div>

@endsection
