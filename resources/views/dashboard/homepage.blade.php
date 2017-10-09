@extends('app')

@section('content')

    <div class="page_header">
        <h1><i class="fa fa-home"></i>
            {{Config::get('agorakit.name')}}
        </h1>
    </div>


    @include('dashboard.tabs')

    <div class="tab_content">


        <h2>{{ trans('messages.my_groups') }}</h2>
        @foreach ($my_groups as $group)
            <span class="label label-default"><a href="{{route('groups.show', $group)}}">{{$group->name}}</a> </span>
        @endforeach



        <div class="row">
            @if (isset($my_discussions))
                <div class="col-md-6">
                    <h2>{{ trans('messages.latest_discussions_my') }}</h2>



                    <table class="table table-hover special">
                        <thead>
                            <tr>
                                <th class="avatar"></th>
                                <th class="summary"></th>
                                <th class="date"></th>
                                <th class="unread"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse( $my_discussions as $discussion )
                                <tr>

                                    <td class="avatar"><span class="avatar"><img src="{{$discussion->user->avatar()}}" class="img-circle"/></span></td>
                                    <td class="content">
                                        <a href="{{ route('groups.discussions.show', [$discussion->group_id, $discussion->id]) }}">
                                            <span class="name">{{ $discussion->name }}</span>
                                            <span class="summary">{{summary($discussion->body) }}</span>
                                            <br/>
                                        </a>
                                        <span class="group-name"><a href="{{ route('groups.show', [$discussion->group_id]) }}">{{ $discussion->group->name }}</a></span>
                                    </td>

                                    <td class="date">
                                        {{ $discussion->updated_at->diffForHumans() }}
                                    </td>

                                    <td>
                                        @if ($discussion->unReadCount() > 0)
                                            <i class="fa fa-comment"></i>
                                            <span class="badge">{{ $discussion->unReadCount() }}</span>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                {{trans('messages.nothing_yet')}}
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            @if (isset($my_actions))
                <div class="col-md-6">
                    <h2>{{ trans('messages.agenda_my') }}</h2>


                    <table class="table table-hover special">
                        <thead>
                            <tr>
                                <th style="width: 50%">{{ trans('messages.title') }}</th>
                                <th>{{ trans('messages.date') }}</th>
                                <th>{{ trans('messages.where') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach( $my_actions as $action )
                                <tr>
                                    <td class="content">
                                        <a href="{{ route('groups.actions.show', [$action->group_id, $action->id]) }}">
                                            <span class="name">{{ $action->name }}</span>
                                            <span class="summary">{{ summary($action->body) }}</span>
                                        </a>
                                        <br/>
                                        <span class="group-name"><a href="{{ route('groups.show', [$action->group_id]) }}">{{ $action->group->name }}</a></span>
                                    </td>

                                    <td>
                                        {{$action->start->format('d/m/Y H:i')}}
                                    </td>

                                    <td class="content">
                                        {{$action->location}}
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            @endif
        </div>


        <div class="row">
            <div class="col-md-6">
                @if (Auth::guest())
                    <h1>{{ trans('messages.latest_discussions') }}</h1>
                @else
                    <h2>{{ trans('messages.latest_discussions_others') }}</h2>
                @endif
                <table class="table table-hover special">
                    <thead>
                        <tr>
                            <th class="avatar"></th>
                            <th class="summary"></th>
                            <th class="date"></th>
                            <th class="unread"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse( $other_discussions as $discussion )
                            <tr>

                                <td class="avatar"><span class="avatar"><img src="{{$discussion->user->avatar()}}" class="img-circle"/></span></td>
                                <td class="content">
                                    <a href="{{ route('groups.discussions.show', [$discussion->group_id, $discussion->id]) }}">
                                        <span class="name">{{ $discussion->name }}</span>
                                        <span class="summary">{{summary($discussion->body) }}</span>
                                        <br/>
                                    </a>
                                    <span class="group-name"><a href="{{ route('groups.show', [$discussion->group_id]) }}">{{ $discussion->group->name }}</a></span>
                                </td>

                                <td class="date">
                                    {{ $discussion->updated_at->diffForHumans() }}
                                </td>

                                <td>
                                    @if ($discussion->unReadCount() > 0)
                                        <i class="fa fa-comment"></i>
                                        <span class="badge">{{ $discussion->unReadCount() }}</span>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            {{trans('messages.nothing_yet')}}
                        @endforelse
                    </tbody>
                </table>
            </div>



            <div class="col-md-6">

                @if (Auth::guest())
                    <h1>{{ trans('messages.agenda') }}</h1>
                @else
                    <h2>{{ trans('messages.agenda_others') }}</h2>
                @endif

                <table class="table table-hover special">
                    <thead>
                        <tr>
                            <th style="width: 50%">{{ trans('messages.title') }}</th>
                            <th>{{ trans('messages.date') }}</th>
                            <th>{{ trans('messages.where') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach( $other_actions as $action )
                            <tr>
                                <td class="content">
                                    <a href="{{ route('groups.actions.show', [$action->group_id, $action->id]) }}">
                                        <span class="name">{{ $action->name }}</span>
                                        <span class="summary">{{ summary($action->body) }}</span>
                                    </a>
                                    <br/>
                                    <span class="group-name"><a href="{{ route('groups.show', [$action->group_id]) }}">{{ $action->group->name }}</a></span>
                                </td>

                                <td>
                                    {{$action->start->format('d/m/Y H:i')}}
                                </td>

                                <td class="content">
                                    {{$action->location}}
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>


        {!! setting('homepage_presentation', trans('documentation.intro')) !!}

        @if (Auth::user()->isAdmin())
            <div>
                <a class="btn btn-primary" href="{{action('Admin\SettingsController@index')}}">
                    <i class="fa fa-pencil"></i> {{trans('messages.modify_intro_text')}}
                </a>
            </div>
        @endif

    </div>

@endsection
