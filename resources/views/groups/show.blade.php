@extends('group')

@section('content')
    <div class="row mb-4">

        <div class="col">

            <div>
                {!! filter($group->body) !!}
            </div>

            @if (isset($admins) && $admins->count() > 0)
                <div class="mb-3">
                    <div class="font-bold">
                        {{ trans('messages.group_admin_users') }}
                    </div>

                    @foreach ($admins as $admin)
                        <a class="mr-2" href="{{ route('users.show', [$admin]) }}">{{ $admin->name }}</a>
                    @endforeach
                </div>
            @endif

            @if ($group_inbox)
                <div class="mb-3">
                    <div class="font-bold">{{ __('Inbox for this group') }}</div>
                    <a href="mailto:{{ $group_inbox }}" up-follow>{{ $group_inbox }}</a>
                    <div class="small-help">{{ trans('messages.inbox_help') }}</div>
                </div>
            @endif

            <div class="mb-3">
                <div class="font-bold">{{ __('Creation date') }}</div>
                {{ $group->created_at->diffForHumans() }}
            </div>

            <div class="mb-3">
                <div class="font-bold">{{ __('Group type') }}</div>
                @if ($group->isOpen())
                    <i class="fa fa-globe" title="{{ trans('group.open') }}">
                    </i>
                    @lang('This group is public and open to members')
                @elseif ($group->isClosed())
                    <i class="fa fa-lock" title="{{ trans('group.closed') }}">
                    </i>
                    @lang('This group is private. Users must apply to join')
                @else
                    <i class="fa fa-eye-slash" title="{{ trans('group.secret') }}"></i>
                    @lang('This group is secret and only visible to it\'s members')
                @endif

            </div>

            <div class="mb-3">
                <div class="font-bold">{{ __('Stats & keywords') }}</div>
                <span class="badge badge-secondary"><i class="fa fa-users"></i> {{ $group->users()->count() }}</span>
                <span class="badge badge-secondary"><i class="fa fa-comments"></i>
                    {{ $group->discussions()->count() }}</span>
                <span class="badge badge-secondary"><i class="fa fa-calendar"></i>
                    {{ $group->actions()->count() }}</span>
                @foreach ($group->getSelectedTags() as $tag)
                    @include('tags.tag')
                @endforeach
            </div>

        </div>

        <div class="col">
            @if ($group->hasCover())
                <img class="rounded" src="{{ route('groups.cover.large', $group) }}" />
            @else
                <img class="rounded" src="/images/group.svg" />
            @endif
        </div>

    </div>

    @if ($discussions)
    @include('discussions.list', ['discussions' => $discussions])
    @endif

    @if ($actions)
        @if ($actions->count() > 0)
            <h2 class="my-4 d-flex justify-between">
                <a href="{{ route('groups.actions.index', $group) }}" up-follow>{{ trans('messages.agenda') }}</a>
                @can('create-action', $group)
                    <a class="btn btn-primary" href="{{ route('groups.actions.create', $group) }}">
                        {{ trans('action.create_one_button') }}
                    </a>
                @endcan
            </h2>
            <div class="actions">
                @foreach ($actions as $action)
                    <x-action :action="$action" :participants="true" />
                @endforeach
            </div>
        @endif
    @endif

    @if ($files)
        @if ($files->count() > 0)
            <h2 class="mb-4 mt-5"><a href="{{ route('groups.files.index', $group) }}" up-follow>{{ trans('group.latest_files') }}</a></h2>
            <div class="files">
                @forelse( $files as $file )
                    @include('files.file')
                @endforeach
            </div>
        @endif
    @endif

@endsection
