@extends('app')

@section('content')

   

    <div class="sm:flex justify-between">
        <h1><i class="fa fa-search"></i> {{trans('messages.your_search_for')}} <strong>"{{$query}}"</strong></h1>
        @include('partials.preferences-show')
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
                        @include('groups.group-list')
                    @endforeach
                </div>
            @endif

            @if ($discussions->count() > 0)
                <div role="tabpanel" class="tab-pane {{$discussions->class}}" id="discussions">
                    @foreach ($discussions as $discussion)
                         @include('discussions.discussion')
                    @endforeach
                </div>
            @endif

            @if ($actions->count() > 0)
                <div role="tabpanel" class="tab-pane {{$actions->class}}" id="actions">
                    @foreach ($actions as $action)
                         @include('actions.action')
                    @endforeach
                </div>
            @endif


            @if ($users->count() > 0)
                <div role="tabpanel" class="tab-pane {{$users->class}}" id="users">
                    @foreach ($users as $user)
                        @include('users.user-list')
                    @endforeach
                </div>
            @endif

            @if ($comments->count() > 0)
                <div role="tabpanel" class="tab-pane {{$comments->class}}" id="comments">
                    @foreach ($comments as $comment)
                     @include('comments.comment')
                    @endforeach
                </div>
            @endif


            @if ($files->count() > 0)
                <div role="tabpanel" class="tab-pane {{$files->class}}" id="files">
                    @foreach ($files as $file)
                         @include('files.file')
                    @endforeach
                </div>
            @endif


        </div>
    </div>
</div>
@endsection
