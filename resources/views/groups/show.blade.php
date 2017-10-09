@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        <div class="row">
            <div class="col-md-6">
                <p>
                    {!! filter($group->body) !!}
                </p>

                <p>

                    @if (isset($admins) && $admins->count() > 0)
                        {{trans('messages.group_admin_users')}} :

                        @foreach ($admins as $admin)
                            <a href="{{ route('users.show', [$admin->id]) }}">{{$admin->name}}</a>
                        @endforeach
                    @endif

                </p>

                <p>
                    @if ($group->tags->count() > 0)
                        {{trans('messages.tags')}} :
                        @foreach ($group->tags as $tag)
                            <span class="label label-default">{{$tag->name}}</span>
                        @endforeach
                    @endif
                </p>

                <p>
                    @can('history', $group)
                        @if ($group->revisionHistory->count() > 0)
                            <a class="btn btn-default btn-xs" href="{{route('groups.history', $group->id)}}">
                                <i class="fa fa-history"></i>
                                {{trans('messages.show_history')}}
                            </a>
                        @endif
                    @endcan
                </p>
            </div>

            <div class="col-md-6">
                <img class="img-responsive img-rounded" src="{{route('groups.cover', $group->id)}}"/>
            </div>

        </div>






        @if ($actions)
            @if($actions->count() > 0)
                <h2><a href="{{ route('groups.actions.index', $group->id) }}">{{trans('messages.agenda')}}</a></h2>


                <table class="table table-hover special">
                    <thead>
                        <tr>
                            <th>{{trans('messages.date')}}</th>
                            <th style="width: 75%">{{trans('messages.title')}}</th>

                            <th>{{trans('messages.where')}}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($actions as $action)
                            <tr>

                                <td>
                                    {{$action->start->format('d/m/Y H:i')}}
                                </td>

                                <td class="content">
                                    <a href="{{ route('groups.actions.show', [$group->id, $action->id]) }}">
                                        <span class="name">{{ $action->name }}</span>
                                        <span class="summary">{{ summary($action->body) }}</span></a>
                                    </td>

                                    <td class="content">
                                        {{$action->location}}
                                    </td>

                                </tr>

                            @endforeach
                        </table>

                    @endif

                @endif




                @if ($discussions)
                    @if($discussions->count() > 0)
                        <h2><a href="{{ route('groups.discussions.index', $group->id) }}">{{trans('group.latest_discussions')}}</a></h2>
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
                                @foreach( $discussions as $discussion )
                                    <tr>

                                        <td class="avatar"><span class="avatar"><img src="{{$discussion->user->avatar()}}" class="img-circle"/></span></td>
                                        <td class="content">
                                            <a href="{{ route('groups.discussions.show', [$discussion->group_id, $discussion->id]) }}">
                                                <span class="name">{{ $discussion->name }}</span>
                                                <span class="summary">{{summary($discussion->body) }}</span>
                                                <br/>
                                            </a>
                                            <span class="group-name"><a href="{{ route('groups.show', [$discussion->group]) }}">{{ $discussion->group->name }}</a></span>
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
                                @endforeach
                            </tbody>
                        </table>


                    @endif
                @endif






                @if ($files)
                    @if($files->count() > 0)
                        <h2><a href="{{ route('groups.files.index', $group->id) }}">{{trans('group.latest_files')}}</a></h2>

                        <table class="table table-hover">
                            @foreach ($files as $file)
                                <tr>
                                    <td>
                                        <a href="{{ route('groups.files.download', [$group->id, $file->id]) }}"><img src="{{ route('groups.files.thumbnail', [$group->id, $file->id]) }}"/></a>
                                    </td>

                                    <td>
                                        <a href="{{ route('groups.files.download', [$group->id, $file->id]) }}">{{ $file->name }}</a>
                                    </td>

                                    <td>
                                        <a href="{{ route('groups.files.download', [$group->id, $file->id]) }}">{{trans('file.download')}}</a>
                                    </td>

                                    <td>
                                        @unless (is_null ($file->user))
                                            <a href="{{ route('users.show', $file->user->id) }}">{{ $file->user->name }}</a>
                                        @endunless
                                    </td>

                                    <td>
                                        {{ $file->created_at->diffForHumans() }}
                                    </td>

                                </tr>

                            @endforeach
                        </table>

                    @endif
                @endif


                @if ($activities)
                    <h4>{{trans('messages.recent_activity')}}</h4>
                    @each('partials.activity-small', $activities, 'activity')
                @endif

            </div>

        @endsection
