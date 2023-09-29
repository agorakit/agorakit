@extends('app')

@section('content')

    <div class="d-flex flex-wrap gap-2 justify-content-between">
        <h1><i class="fa fa-search"></i> {{ trans('messages.your_search_for') }} <strong>"{{ $query }}"</strong></h1>
        @include('partials.preferences-show')
    </div>


    @include ('search.form')

    <div class="search_results">
        <ul class="nav nav-tabs mt-4">
            @if ($groups->count() > 0)
                <li class="nav-item">
                    <button class="nav-link {{ $groups->class }}" href="#groups" aria-controls="groups" role="tab"
                        data-bs-toggle="tab">{{ trans('messages.groups') }} <span
                            class="badge badge-secondary ms-2">{{ $groups->count() }}</span></button>
                </li>
            @endif

            @if ($discussions->count() > 0)
                <li class="nav-item">
                    <button class="nav-link {{ $discussions->class }}" href="#discussions" aria-controls="discussions"
                        role="tab" data-bs-toggle="tab">{{ trans('messages.discussions') }} <span
                            class="badge badge-secondary ms-2">{{ $discussions->count() }}</span></button>
                </li>
            @endif

            @if ($actions->count() > 0)
                <li class="nav-item">
                    <button class="nav-link {{ $actions->class }}" data-bs-target="#actions" aria-controls="actions"
                        role="tab" data-bs-toggle="tab">{{ trans('messages.actions') }} <span
                            class="badge badge-secondary ms-2">{{ $actions->count() }}</span></button>
                </li>
            @endif

            @if ($users->count() > 0)
                <li class="nav-item">
                    <button class="nav-link {{ $users->class }}" data-bs-target="#users" aria-controls="users" role="tab"
                        data-bs-toggle="tab">{{ trans('messages.users') }} <span
                            class="badge badge-secondary ms-2">{{ $users->count() }}</span></button>
                </li>
            @endif

            @if ($comments->count() > 0)
                <li class="nav-item">
                    <button class="nav-link {{ $comments->class }}" data-bs-target="#comments" aria-controls="users"
                        role="tab" data-bs-toggle="tab">{{ trans('messages.comments') }} <span
                            class="badge badge-secondary ms-2"> {{ $comments->count() }}</span></button>
                </li>
            @endif

            @if ($files->count() > 0)
                <li class="nav-item">
                    <button class="nav-link {{ $files->class }}" data-bs-target="#files" aria-controls="users" role="tab"
                        data-bs-toggle="tab">{{ trans('messages.files') }} <span
                            class="badge badge-secondary ms-2">{{ $files->count() }}</span></button>
                </li>
            @endif

        </ul>

        <div class="tab-content mt-4">

            @if ($groups->count() > 0)
                <div role="tabpanel" class="tab-pane {{ $groups->class }}" id="groups">
                    @foreach ($groups as $group)
                        @include('groups.group-list')
                    @endforeach
                    {!! $groups->render() !!}
                </div>
            @endif

            @if ($discussions->count() > 0)
                <div role="tabpanel" class="tab-pane {{ $discussions->class }}" id="discussions">
                    @foreach ($discussions as $discussion)
                        @include('discussions.discussion')
                    @endforeach
                    {!! $discussions->render() !!}
                </div>
            @endif

            @if ($actions->count() > 0)
                <div role="tabpanel" class="tab-pane {{ $actions->class }}" id="actions">
                    @include('actions.list', ['actions' => $actions])
                    {!! $actions->render() !!}
                </div>
            @endif

            @if ($users->count() > 0)
                <div role="tabpanel" class="tab-pane {{ $users->class }}" id="users">
                    @foreach ($users as $user)
                        @include('users.user-list')
                    @endforeach
                    {!! $users->render() !!}
                </div>
            @endif

            @if ($comments->count() > 0)
                <div role="tabpanel" class="tab-pane {{ $comments->class }}" id="comments">
                    @foreach ($comments as $comment)
                        @include('comments.comment')
                    @endforeach
                    {!! $comments->render() !!}
                </div>
            @endif

            @if ($files->count() > 0)
                <div role="tabpanel" class="tab-pane {{ $files->class }}" id="files">
                    @foreach ($files as $file)
                        @include('files.file')
                    @endforeach
                    {!! $files->render() !!}
                </div>
            @endif

        </div>
    </div>
    </div>
@endsection
