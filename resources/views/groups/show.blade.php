@extends('group')

@section('content')
    <div class="row mb-4">

        <div class="col-12 col-md-6 mb-2 order-md-2">
            @if ($group->hasCover())
                <img class="rounded" src="{{ route('groups.cover', [$group, 'large']) }}" />
            @else
                <img class="rounded" src="/images/group.svg" />
            @endif
        </div>

        <div class="col-12 col-md-6">

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

    </div>

@endsection
