<div up-expand>
    <div class="@if ($group->isArchived()) status-archived @endif">

        <a up-follow href="{{ action('GroupController@show', $group) }}">
            @if ($group->hasCover())
                <img class="card-img-top" src="{{ route('groups.cover.medium', $group)}}" />
            @else
                <img class="card-img-top" src="/images/group.svg"/>
            @endif
        </a>

        <div class="card-body">
            <h5 class="card-title">
                <a up-follow href="{{ action('GroupController@show', $group) }}">
                    {{ $group->name }}
                </a>
                @if ($group->isOpen())
                    <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
                @elseif ($group->isClosed())
                    <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
                @else
                    <i class="fa fa-eye-slash" title="{{trans('group.secret')}}"></i>
                @endif
                @if ($group->isPinned())
                    <div class="badge badge-primary" style="min-width: 2em; margin: 0 2px; font-size: 0.5em;">{{__('Pinned')}}</div>
                @endif
                @if ($group->isArchived())
                    <div class="badge badge-muted" style="min-width: 2em; margin: 0 2px; font-size: 0.5em;">{{__('Archived')}}</div>
                @endif
            </h5>
            <p class="card-text">
                <div style="max-height:8em; overflow: hidden">
                    {{summary($group->body) }}
                </div>
                <span class="badge badge-secondary"><i class="fa fa-users"></i> {{$group->users()->count()}}</span>
                <span class="badge badge-secondary"><i class="fa fa-comments"></i> {{$group->discussions()->count()}}</span>
                <span class="badge badge-secondary"><i class="fa fa-calendar"></i> {{$group->actions()->count()}}</span>
                <div style="max-height:3.8em; overflow: hidden">
                    @foreach ($group->tags as $tag)
                        @include('tags.tag')
                    @endforeach
                </div>
            </p>

            <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                    <a class="btn btn-primary" href="{{ action('GroupController@show', $group) }}"></i>
                        {{ trans('messages.visit') }}
                    </a>

                    @unless ($group->isMember())
                        @can ('join', $group)
                            <a class="btn btn-secondary" href="{{ action('GroupMembershipController@store', $group) }}">
                                {{ trans('group.join') }}
                            </a>
                        @endcan
                    @endunless
                </div>
                <small class="text-muted">{{ $group->updated_at->diffForHumans() }}</small>
            </div>



        </div>
    </div>
</div>
