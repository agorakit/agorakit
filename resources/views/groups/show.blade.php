@extends('app')

@section('content')
    <div class="row mb-4">

        <div class="col-12 col-md-6 mb-2 order-md-2">
            @if ($group->hasCover())
                <img alt="" class="rounded" src="{{ route('groups.cover', [$group, 'large']) }}" />
            @else
                <img alt="" class="rounded" src="/images/group.svg" />
            @endif
        </div>

        <div class="col-12 col-md-6">

            <div>
                {!! filter($group->body) !!}
            </div>

            @if (isset($admins) && $admins->count() > 0)
                <div class="mb-3">
                    <div class="font-bold">
                        {{ t('Contact person(s)') }}
                    </div>

                    @foreach ($admins as $admin)
                        <a class="me-2" href="{{ route('users.show', [$admin]) }}">{{ $admin->name }}</a>
                    @endforeach
                </div>
            @endif

            @if ($group_inbox)
                <div class="mb-3">
                    <div class="font-bold">{{ t('Inbox for this group') }}</div>
                    <a href="mailto:{{ $group_inbox }}">{{ $group_inbox }}</a>
                    <div class="small-help">{{ t('The group email can only be used by group members') }}</div>
                </div>
            @endif

            <div class="mb-3">
                <div class="fw-bold">{{ t('Creation date') }}</div>
                {{ $group->created_at->diffForHumans() }}
            </div>

            <div class="mb-3">
                <div class="fw-bold">{{ t('Group type') }}</div>
                @if ($group->isOpen())
                    <i class="fa fa-globe" title="{{ t('Public group') }}">
                    </i>
                    @lang('This group is public and open to members')
                @elseif ($group->isClosed())
                    <i class="fa fa-lock" title="{{ t('Closed group') }}">
                    </i>
                    @lang('This group is private. Users must apply to join')
                @else
                    <i class="fa fa-eye-slash" title="{{ t('Secret group') }}"></i>
                    @lang('This group is secret and only visible to it\'s members')
                @endif

            </div>

            <div class="mb-3">
                <div class="fw-bold">{{ t('Stats & keywords') }}</div>
                <div class="mb-2">
                    <span class="badge badge-secondary"><i class="fa fa-users"></i> {{ $group->users()->count() }}</span>
                    <span class="badge badge-secondary"><i class="fa fa-comments"></i>
                        {{ $group->discussions()->count() }}</span>
                    <span class="badge badge-secondary"><i
                            class="fa fa-calendar"></i>{{ $group->calendarevents()->count() }}</span>
                </div>
                <div class="d-flex gap-1 flex-wrap">
                    @foreach ($group->getSelectedTags() as $tag)
                        @include('tags.tag')
                    @endforeach
                </div>
            </div>

            <div class="mb-3">
            @if ($group->locationDisplay("long"))
                <div class="fw-bold">{{ t('Location') }}</div>
                {{ $group->locationDisplay("long") }}
            @endif
            </div>
        </div>

    </div>

@endsection
