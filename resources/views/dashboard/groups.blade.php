@extends('app')

@section('content')

    <div class="page_header">
        <h1><a href="{{ action('DashboardController@index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.all_groups') }}</h1>
    </div>

    @include('dashboard.tabs')

    <div class="tab_content">

        @if ($groups)
            <h2>{{ trans('messages.all_groups') }}</h2>
            <div class="row">
                @forelse( $groups as $group )
                    <div class="col-xs-6 col-md-3">
                        <div class="thumbnail group">
                            <a href="{{ action('GroupController@show', $group->id) }}">
                                <img src="{{action('GroupController@cover', $group->id)}}"/>
                            </a>
                            <div class="caption">
                                <h4>
                                    <a href="{{ action('GroupController@show', $group->id) }}">{{ $group->name }}
                                        @if ($group->isPublic())
                                            <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
                                        @else
                                            <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
                                        @endif

                                    </a>
                                </h4>
                                <p class="summary">{{ summary($group->body, 150) }}</p>
                                <p>



                                    @unless ($group->isMember())
                                        @can ('join', $group)
                                            <a class="btn btn-primary" href="{{ action('MembershipController@join', $group->id) }}"><i class="fa fa-sign-in"></i>
                                                {{ trans('group.join') }}
                                            </a>
                                        @endcan

                                    @endunless

                                    <a class="btn btn-primary" href="{{ action('GroupController@show', $group->id) }}">{{ trans('group.visit') }}</a>

                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    {{trans('group.no_group_yet')}}
                @endforelse

            </div>

        @endif


        @if ($my_groups)
            <h2>{{ trans('messages.my_groups') }}</h2>
            <div class="row">
                @forelse( $my_groups as $group )
                    <div class="col-xs-6 col-md-3">
                        <div class="thumbnail group">
                            <a href="{{ action('GroupController@show', $group->id) }}">
                                <img src="{{action('GroupController@cover', $group->id)}}"/>
                            </a>
                            <div class="caption">
                                <h4>
                                    <a href="{{ action('GroupController@show', $group->id) }}">{{ $group->name }}
                                        @if ($group->isPublic())
                                            <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
                                        @else
                                            <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
                                        @endif

                                    </a>
                                </h4>
                                <p class="summary">{{ summary($group->body, 150) }}</p>
                                <p>



                                    @unless ($group->isMember())
                                        @can ('join', $group)
                                            <a class="btn btn-primary" href="{{ action('MembershipController@join', $group->id) }}"><i class="fa fa-sign-in"></i>
                                                {{ trans('group.join') }}
                                            </a>
                                        @endcan

                                    @endunless

                                    <a class="btn btn-primary" href="{{ action('GroupController@show', $group->id) }}">{{ trans('group.visit') }}</a>

                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <a href="{{ action('DashboardController@index')}}">{{ trans('membership.not_subscribed_to_group_yet') }}</a>
                @endforelse
            </div>

        @endif



        @if ($other_groups)
            <h2>{{ trans('messages.other_groups') }}</h2>
            <div class="row">
                @forelse( $other_groups as $group )
                    <div class="col-xs-6 col-md-3">
                        <div class="thumbnail group">
                            <a href="{{ action('GroupController@show', $group->id) }}">
                                <img src="{{action('GroupController@cover', $group->id)}}"/>
                            </a>
                            <div class="caption">
                                <h4>
                                    <a href="{{ action('GroupController@show', $group->id) }}">{{ $group->name }}
                                        @if ($group->isPublic())
                                            <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
                                        @else
                                            <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
                                        @endif

                                    </a>
                                </h4>
                                <p class="summary">{{ summary($group->body, 150) }}</p>
                                <p>



                                    @unless ($group->isMember())
                                        @can ('join', $group)
                                            <a class="btn btn-primary" href="{{ action('MembershipController@join', $group->id) }}"><i class="fa fa-sign-in"></i>
                                                {{ trans('group.join') }}
                                            </a>
                                        @endcan

                                    @endunless

                                    <a class="btn btn-primary" href="{{ action('GroupController@show', $group->id) }}">{{ trans('group.visit') }}</a>

                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    {{trans('group.no_group_yet')}}
                @endforelse

            </div>

        @endif

        


    </div>

@endsection
