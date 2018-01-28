@extends('app')

@section('content')

    <div class="page_header">
        <h1><i class="fa fa-search"></i>{{trans('messages.your_search_for')}} <strong>{{$query}}</strong></h1>
    </div>

    <div class="search_results">
        <ul class="nav nav-tabs" role="tablist">
            @if ($groups->count() > 0)
                <li role="presentation" class="{{$groups->class}}">
                    <a href="#groups" aria-controls="groups" role="tab" data-toggle="tab">{{trans('messages.groups')}} <span class="badge badge-secondary">{{$groups->count()}}</span></a>
                </li>
            @endif

            @if ($discussions->count() > 0)
                <li role="presentation" class="{{$discussions->class}}">
                    <a href="#discussions" aria-controls="discussions" role="tab" data-toggle="tab">{{trans('messages.discussions')}} <span class="badge badge-secondary">{{$discussions->count()}}</span></a>
                </li>
            @endif

            @if ($actions->count() > 0)
                <li role="presentation" class="{{$actions->class}}">
                    <a href="#actions" aria-controls="actions" role="tab" data-toggle="tab">{{trans('messages.actions')}} <span class="badge badge-secondary">{{$actions->count()}}</span></a>
                </li>
            @endif

            @if ($users->count() > 0)
                <li role="presentation" class="{{$users->class}}">
                    <a href="#users" aria-controls="users" role="tab" data-toggle="tab">{{trans('messages.users')}} <span class="badge badge-secondary">{{$users->count()}}</span></a>
                </li>
            @endif

            @if ($comments->count() > 0)
                <li role="presentation" class="{{$comments->class}}">
                    <a href="#comments" aria-controls="users" role="tab" data-toggle="tab">{{trans('messages.comments')}} <span class="badge badge-secondary">{{$comments->count()}}</span></a>
                </li>
            @endif

            @if ($files->count() > 0)
                <li role="presentation" class="{{$files->class}}">
                    <a href="#files" aria-controls="users" role="tab" data-toggle="tab">{{trans('messages.files')}} <span class="badge badge-secondary">{{$files->count()}}</span></a>
                </li>
            @endif

        </ul>

        <div class="tab_content">
            <div class="tab-content">

                @if ($groups->count() > 0)
                    <div role="tabpanel" class="tab-pane {{$groups->class}}" id="groups">
                        <h2>{{trans('messages.groups')}}</h2>
                        @foreach ($groups as $group)
                            <div class="result">
                                <h4><a href="{{$group->link()}}">{{$group->name}}</a></h4>
                                {{summary($group->body, 500)}}
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($discussions->count() > 0)
                    <div role="tabpanel" class="tab-pane {{$discussions->class}}" id="discussions">
                        <h2>{{trans('messages.discussions')}}</h2>
                        @foreach ($discussions as $discussion)
                            <div class="result">
                                <h4><a href="{{$discussion->link()}}">{{$discussion->name}}</a></h4>
                                {{summary($discussion->body, 500)}}
                                <br/>
                                <span class="badge badge-secondary">{{$discussion->group->name}}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($actions->count() > 0)
                    <div role="tabpanel" class="tab-pane {{$actions->class}}" id="actions">
                        <h2>{{trans('messages.actions')}}</h2>
                        @foreach ($actions as $action)
                            <div class="result">
                                <h4><a href="{{$action->link()}}">{{$action->name}}</a></h4>
                                {{summary($action->body)}}
                                <br/>
                                {{$action->start}} / {{$action->stop}}
                                <br/>
                                <span class="badge badge-secondary">{{$action->group->name}}</span>
                            </div>
                        @endforeach
                    </div>
                @endif


                @if ($users->count() > 0)
                    <div role="tabpanel" class="tab-pane {{$users->class}}" id="users">
                        <h2>{{trans('messages.users')}}</h2>
                        @foreach ($users as $user)
                            <div class="result">
                                <h4><span class="avatar"><img src="{{$user->avatar()}}" class="rounded-circle"/></span> <a href="{{$user->link()}}">{{$user->name}}</a></h4>
                                {{summary($user->body)}}
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($comments->count() > 0)
                    <div role="tabpanel" class="tab-pane {{$comments->class}}" id="comments">
                        <h2>{{trans('messages.comments')}}</h2>
                        @foreach ($comments as $comment)
                            <div class="result">
                                <h4><a href="{{$comment->link()}}">{{$comment->discussion->name}}</a></h4>
                                {{summary($comment->body)}}
                                <br/>
                                <span class="badge badge-secondary">{{$comment->discussion->group->name}}</span>
                            </div>
                        @endforeach
                    </div>
                @endif


                @if ($files->count() > 0)
                    <div role="tabpanel" class="tab-pane {{$files->class}}" id="files">
                        <h2>{{trans('messages.files')}}</h2>
                        @foreach ($files as $file)
                            <div class="result">
                                <h4><a href="{{$file->link()}}">{{$file->name}}</a></h4>
                                <span class="badge badge-secondary">{{$file->group->name}}</span>
                            </div>
                        @endforeach
                    </div>
                @endif


            </div>
        </div>
    </div>
@endsection
