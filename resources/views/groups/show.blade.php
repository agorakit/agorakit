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
                        <a class="me-2" href="{{ route('users.show', [$admin]) }}">{{ $admin->name }}</a>
                    @endforeach
                </div>
            @endif

            @if ($group_inbox)
                <div class="mb-3">
                    <div class="font-bold">{{ __('Inbox for this group') }}</div>
                    <a href="mailto:{{ $group_inbox }}">{{ $group_inbox }}</a>
                    <div class="small-help">{{ trans('messages.inbox_help') }}</div>
                </div>
            @endif

            <div class="mb-3">
                <div class="fw-bold">{{ __('Creation date') }}</div>
                {{ $group->created_at->diffForHumans() }}
            </div>

            <div class="mb-3">
                <div class="fw-bold">{{ __('Group type') }}</div>
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
                <div class="fw-bold">{{ __('Stats & keywords') }}</div>
                <div class="mb-2">
                    <span class="badge badge-secondary"><i class="fa fa-users"></i> {{ $group->users()->count() }}</span>
                    <span class="badge badge-secondary"><i class="fa fa-comments"></i>
                        {{ $group->discussions()->count() }}</span>
                    <span class="badge badge-secondary"><i
                            class="fa fa-calendar"></i>{{ $group->actions()->count() }}</span>
                </div>
                <div class="d-flex gap-1 flex-wrap">
                    @foreach ($group->getSelectedTags() as $tag)
                        @include('tags.tag')
                    @endforeach
                </div>
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
            <h2 class="mt-4">
                <a href="{{ route('groups.actions.index', $group) }}">{{ trans('messages.agenda') }}</a>
            </h2>
            <div class="actions">
                @include('actions.list', ['actions' => $actions])
            </div>
        @endif
    @endif

    @if ($files)
        @if ($files->count() > 0)
            <h2 class="mb-4 mt-5"><a
                    href="{{ route('groups.files.index', $group) }}">{{ trans('group.latest_files') }}</a></h2>
            <div class="files">
                @forelse( $files as $file )
                    @include('files.file')
                @endforeach
            </div>
        @endif
    @endif

@endsection
