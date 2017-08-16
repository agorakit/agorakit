@extends('app')

@section('content')

    <div class="page_header">
        <h1><a href="{{ action('DashboardController@index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.all_groups') }}</h1>
    </div>

    @include('dashboard.tabs')

    <div class="tab_content">


        @push ('js')

            <script>
            $(document).ready(function(){
                @foreach ($all_tags as $tag)
                $("#toggle-tag-{{$tag->tag_id}}").click(function(){
                    $(".tag-{{$tag->tag_id}}").toggle();
                    $(this).toggleClass("btn-checked");
                });
                @endforeach
            });

            </script>
        @endpush




        @foreach ($all_tags as $tag)
            <input type="checkbox" checked="checked" id="toggle-tag-{{$tag->tag_id}}"></input>
            <label for="toggle-tag-{{$tag->tag_id}}">{{$tag->name}}</label>

        @endforeach





        @if ($groups)
            <h2>{{ trans('messages.all_groups') }}</h2>
            <div class="row">
                @forelse( $groups as $group )
                    <div class="col-xs-6 col-md-3 @foreach ($group->tags as $tag)tag-{{$tag->tag_id}} @endforeach">
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

                                <p>
                                    @foreach ($group->tags as $tag)
                                        <span class="label label-default">{{$tag->name}} {{$tag->tag_id}}</span>
                                    @endforeach
                                </p>

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
                    <div class="col-xs-6 col-md-3 @foreach ($group->tags as $tag)tag-{{$tag->tag_id}} @endforeach">
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

                                <p>
                                    @foreach ($group->tags as $tag)
                                        <span class="label label-default">{{$tag->name}}</span>
                                    @endforeach
                                </p>

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
                    <div class="col-xs-6 col-md-3 @foreach ($group->tags as $tag)tag-{{$tag->tag_id}} @endforeach">
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
                                <p>
                                    @foreach ($group->tags as $tag)
                                        <span class="label label-default">{{$tag->name}}</span>
                                    @endforeach
                                </p>

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
