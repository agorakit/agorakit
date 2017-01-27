@extends('app')

@section('content')

    <div class="page_header">
        <h1><a href="{{ action('DashboardController@index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.all_groups') }}</h1>
    </div>

    <div class="tab_content">
            
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

            <div class="col-xs-6 col-md-3">
                <div class="thumbnail group">
                    <a href="{{ action('GroupController@create') }}">
                        <div style="margin: auto; text-align: center; width: 100%; height:auto;"><i class="fa fa-plus-circle" style="font-size: 150px;" aria-hidden="true"></i></div>

                    </a>
                    <div class="caption">
                        <h4><a href="{{ action('GroupController@create') }}">{{ trans('group.your_group_here') }}</a></h4>
                        <p class="summary">{{ trans('group.create_a_group_intro') }}</p>
                        <p>

                            <a class="btn btn-primary" href="{{ action('GroupController@create') }}">{{ trans('group.create') }}</a>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
