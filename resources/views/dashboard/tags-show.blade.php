@extends('app')

@section('content')

    <div class="flex justify-content-between">
        <h1 class="name mb-4">
            <a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
            <a href="{{ route('tags.index') }}">@lang('Tags')</a> <i class="fa fa-angle-right"></i>
            @lang('Items tagged with')
            <span class="badge" style="background-color: {{ $tag->color }}">{{ $tag->name }}</span>
            </a>

        </h1>

        @auth
            <div class="d-flex mb-2">
                @include('partials.preferences-show')
            </div>
        @endauth

    </div>

    @if ($discussions->count() > 0)
        <div class="mb-5">
            <h2>@lang('Discussions')</h2>
            <div class="discussions items">
                @foreach ($discussions as $discussion)
                    @include('discussions.discussion')
                @endforeach
            </div>
        </div>
    @endif

    @if ($actions->count() > 0)
        <div class="mb-5">
            <h2>@lang('Actions')</h2>
            <div class="actions items">
                @include('actions.list', ['actions' => $actions])
            </div>
        </div>
    @endif

    @if ($files->count() > 0)
        <div class="mb-5">
            <h2>@lang('Files')</h2>
            <div class="files items">
                @foreach ($files as $file)
                    @include('files.file')
                @endforeach
            </div>
        </div>
    @endif

    @if ($users->count() > 0)
        <div class="mb-5">
            <h2>@lang('Users')</h2>
            <div class="users items">
                @foreach ($users as $user)
                    @include('users.user')
                @endforeach
            </div>
        </div>
    @endif

    @if ($groups->count() > 0)
        <div class="mb-5">
            <h2>@lang('Groups')</h2>
            <div class="groups items">
                @foreach ($groups as $group)
                    @include('groups.group-list')
                @endforeach
            </div>
        </div>
    @endif

@endsection
