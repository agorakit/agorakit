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
                    $(".tag-group").hide();
                    $(".tag-{{$tag->tag_id}}").show();
                    $(".tag-toggle").attr('disabled', false);
                    $(this).attr('disabled', true);
                });
                @endforeach

                $("#toggle-tag-all").click(function(){
                    $(".tag-group").show();
                    $(".tag-toggle").attr('disabled', false);
                    $(this).attr('disabled', true);
                });
            });

            </script>
        @endpush




            @foreach ($all_tags as $tag)
                <a class="btn btn-primary btn-sm tag-toggle" id="toggle-tag-{{$tag->tag_id}}">{{$tag->name}}</a>
            @endforeach

            <a class="btn btn-primary btn-sm tag-toggle" id="toggle-tag-all">{{trans('messages.show_all')}}</a>



<div class="tab_content">
    @if ($groups)
        <table class="table table-hover special">
            <thead>
                <tr>
                    <th class="avatar"></th>
                    <th class="summary">Nom</th>
                    <th class="">Dernière activité</th>
                    <th class=""></th>
                </tr>
            </thead>

            <tbody>
                @forelse( $groups as $group )
                    <tr class="tag-group @foreach ($group->tags as $tag)tag-{{$tag->tag_id}} @endforeach">
                        <td class="avatar"><span class="avatar"><img src="{{ action('GroupController@avatar', $group)}}" class="img-rounded"/></span></td>
                        <td class="content">
                            <a href="{{ action('GroupController@show',  $group->id) }}">
                                <span class="name">{{ $group->name }}</span>
                                <span class="summary">{{summary($group->body) }}</span>
                                <br/>
                            </a>
                            <span class="group-name">
                                @foreach ($group->tags as $tag)
                                    <span class="label label-default">{{$tag->name}}</span>
                                @endforeach

                            </span>
                        </td>

                        <td class="date">
                            {{ $group->updated_at->diffForHumans() }}
                        </td>

                        <td>
                            @unless ($group->isMember())
                                @can ('join', $group)
                                    <a class="btn btn-primary btn-sm" href="{{ action('MembershipController@join', $group->id) }}"><i class="fa fa-sign-in"></i>
                                        {{ trans('group.join') }}
                                    </a>
                                @endcan

                            @endunless

                        </td>

                    </tr>
                @empty
                    {{trans('messages.nothing_yet')}}
                @endforelse
            </tbody>
        </table>



    @else
        {{trans('messages.nothing_yet')}}
    @endif
</div>



@if ($groups)
    <h2>{{ trans('messages.all_groups') }}</h2>
    <div class="row">
        @forelse( $groups as $group )
            <div class="col-xs-6 col-md-3 tag-group @foreach ($group->tags as $tag)tag-{{$tag->tag_id}} @endforeach">
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
