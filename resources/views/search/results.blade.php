@extends('app')

@section('content')

    <div class="">
        <h1><i class="fa fa-search"></i> {{trans('messages.your_search_for')}} <strong>"{{$query}}"</strong></h1>
    </div>

    <div class="search_results">
        <ul class="nav nav-tabs mt-4">
            @if ($groups->count() > 0)
                <li class="nav-item">
                    <a class="nav-link {{$groups->class}}" href="#groups" aria-controls="groups" role="tab" data-toggle="tab">{{trans('messages.groups')}} <span class="badge badge-secondary">{{$groups->count()}}</span></a>
                </li>
            @endif

            @if ($discussions->count() > 0)
                <li class="nav-item">
                    <a class="nav-link {{$discussions->class}}" href="#discussions" aria-controls="discussions" role="tab" data-toggle="tab">{{trans('messages.discussions')}} <span class="badge badge-secondary">{{$discussions->count()}}</span></a>
                </li>
            @endif

            @if ($actions->count() > 0)
                <li class="nav-item">
                    <a class="nav-link {{$actions->class}}"href="#actions" aria-controls="actions" role="tab" data-toggle="tab">{{trans('messages.actions')}} <span class="badge badge-secondary">{{$actions->count()}}</span></a>
                </li>
            @endif

            @if ($users->count() > 0)
                <li class="nav-item">
                    <a class="nav-link {{$users->class}}"href="#users" aria-controls="users" role="tab" data-toggle="tab">{{trans('messages.users')}} <span class="badge badge-secondary">{{$users->count()}}</span></a>
                </li>
            @endif

            @if ($comments->count() > 0)
                <li class="nav-item">
                    <a  class="nav-link {{$comments->class}}"href="#comments" aria-controls="users" role="tab" data-toggle="tab">{{trans('messages.comments')}} <span class="badge badge-secondary">{{$comments->count()}}</span></a>
                </li>
            @endif

            @if ($files->count() > 0)
                <li class="nav-item">
                    <a class="nav-link {{$files->class}}" href="#files" aria-controls="users" role="tab" data-toggle="tab">{{trans('messages.files')}} <span class="badge badge-secondary">{{$files->count()}}</span></a>
                </li>
            @endif

        </ul>

        <div class="tab-content mt-4">

            @if ($groups->count() > 0)
                <div role="tabpanel" class="tab-pane {{$groups->class}}" id="groups">
                    @foreach ($groups as $group)
                        <div class="result">
                            <h4><a up-follow href="{{$group->link()}}">{{$group->name}}</a></h4>
                            {{summary($group->body, 500)}}
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($discussions->count() > 0)
                <div role="tabpanel" class="tab-pane {{$discussions->class}}" id="discussions">
                    @foreach ($discussions as $discussion)
                        <div class="result">
                            <h4><a up-follow href="{{$discussion->link()}}">{{$discussion->name}}</a></h4>
                            {{summary($discussion->body, 500)}}
                            <br/>
                            <span class="badge badge-secondary badge-group">
                                @if ($discussion->group->isOpen())
                                    <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
                                @elseif ($discussion->group->isClosed())
                                    <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
                                @else
                                    <i class="fa fa-eye-slash" title="{{trans('group.secret')}}"></i>
                                @endif
                                {{ $discussion->group->name }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($actions->count() > 0)
                <div role="tabpanel" class="tab-pane {{$actions->class}}" id="actions">
                    @foreach ($actions as $action)
                        <div class="result">
                            <h4><a up-follow href="{{$action->link()}}">{{$action->name}}</a></h4>
                            {{summary($action->body)}}
                            <br/>
                            {{$action->start}} / {{$action->stop}}
                            <br/>
                            <span class="badge badge-secondary badge-group">
                                @if ($action->group->isOpen())
                                    <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
                                @elseif ($action->group->isClosed())
                                    <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
                                @else
                                    <i class="fa fa-eye-slash" title="{{trans('group.secret')}}"></i>
                                @endif
                                {{ $action->group->name }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif


            @if ($users->count() > 0)
                <div role="tabpanel" class="tab-pane {{$users->class}}" id="users">
                    @foreach ($users as $user)
                        <div class="result">
                            <h4><span class="avatar"><img src="{{route('users.cover', [$user, 'small'])}}" class="rounded-full"/></span> <a up-follow href="{{$user->link()}}">{{$user->name}}</a></h4>
                            {{summary($user->body)}}
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($comments->count() > 0)
                <div role="tabpanel" class="tab-pane {{$comments->class}}" id="comments">
                    @foreach ($comments as $comment)
                        <div class="result">
                            <h4><a up-follow href="{{$comment->link()}}">{{$comment->discussion->name}}</a></h4>
                            {{summary($comment->body)}}
                            <br/>
                            <span class="badge badge-secondary badge-group">
                                @if ($comment->discussion->group->isOpen())
                                    <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
                                @elseif ($comment->discussion->group->isClosed())
                                    <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
                                @else
                                    <i class="fa fa-eye-slash" title="{{trans('group.secret')}}"></i>
                                @endif
                                {{ $comment->discussion->group->name }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif


            @if ($files->count() > 0)
                <div role="tabpanel" class="tab-pane {{$files->class}}" id="files">
                    @foreach ($files as $file)
                        <div class="result">
                            <h4><a up-follow href="{{ route('groups.files.show', [$file->group, $file]) }}">{{$file->name}}</a></h4>
                            <span class="badge badge-secondary badge-group">
                                @if ($file->group->isOpen())
                                    <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
                                @elseif ($file->group->isClosed())
                                    <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
                                @else
                                    <i class="fa fa-eye-slash" title="{{trans('group.secret')}}"></i>
                                @endif
                                {{ $file->group->name }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif


        </div>
    </div>
</div>
@endsection
