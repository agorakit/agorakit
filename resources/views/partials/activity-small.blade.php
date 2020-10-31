@if ($activity->getType() == 'discussion')
    <div class="activity-small">
        <span class="avatar"><img src="{{{{route('users.cover', [$activity->user, 'small'])}}}}" class="rounded-full"/></span>
        <a up-follow href="{{route('users.show', $activity->user)}}">{{$activity->user->name}}</a>
        {{trans('messages.activity_' . $activity->action)}}
        <a up-follow href="{{$activity->linkToModel()}}">{{$activity->model->name}}</a>
        {{$activity->created_at->diffForHumans()}}
        {{trans('messages.in')}}
        <a up-follow href="{{route('groups.show', $activity->group)}}">{{$activity->group->name}}</a>
    </div>
@endif


@if ($activity->getType() == 'action')
    <div class="activity-small">
        <span class="avatar"><img src="{{{{route('users.cover', [$activity->user, 'small'])}}}}" class="rounded-full"/></span>
        <a up-follow href="{{route('users.show', $activity->user)}}">{{$activity->user->name}}</a>
        {{trans('messages.activity_' . $activity->action)}}
        <a up-follow href="{{$activity->linkToModel()}}">{{$activity->model->name}}</a>
        {{$activity->created_at->diffForHumans()}}
        {{trans('messages.in')}}
        <a up-follow href="{{route('groups.show', $activity->group)}}">{{$activity->group->name}}</a>
    </div>
@endif

@if ($activity->getType() == 'file')
    <div class="activity-small">
        <span class="avatar"><img src="{{{{route('users.cover', [$activity->user, 'small'])}}}}" class="rounded-full"/></span>
        <a up-follow href="{{route('users.show', $activity->user)}}">{{$activity->user->name}}</a>
        {{trans('messages.activity_' . $activity->action)}}
        <a up-follow href="{{$activity->linkToModel()}}">{{$activity->model->name}}</a>
        {{$activity->created_at->diffForHumans()}}
        {{trans('messages.in')}}
        <a up-follow href="{{route('groups.show', $activity->group)}}">{{$activity->group->name}}</a>
    </div>
@endif


@if ($activity->getType() == 'comment')
    <div class="activity-small">
        <span class="avatar"><img src="{{{{route('users.cover', [$activity->user, 'small'])}}}}" class="rounded-full"/></span>
        <a up-follow href="{{route('users.show', $activity->user)}}">{{$activity->user->name}}</a>
        {{trans('messages.activity_' . $activity->action)}}
        <a up-follow href="{{$activity->linkToModel()}}">{{$activity->model->discussion->name}}</a>
        {{$activity->created_at->diffForHumans()}}
        {{trans('messages.in')}}
        <a up-follow href="{{route('groups.show', $activity->group)}}">{{$activity->group->name}}</a>
    </div>
@endif
