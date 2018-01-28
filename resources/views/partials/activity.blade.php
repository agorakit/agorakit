@if ($activity->getType() == 'discussion')
    <div class="activity">
        <div class="header">
            <span class="avatar"><img src="{{$activity->user->avatar()}}" class="rounded-circle"/></span>
            <a href="{{route('users.show', $activity->user)}}">{{$activity->user->name}}</a>
            {{trans('messages.activity_' . $activity->action)}}
            <a href="{{$activity->linkToModel()}}">{{$activity->model->name}}</a>
            {{$activity->created_at->diffForHumans()}}
            {{trans('messages.in')}}
            <a href="{{route('groups.show', $activity->group)}}">{{$activity->group->name}}</a>
        </div>

        <div class="detail">
            {{summary($activity->model->body)}}
        </div>

        <div class="action">
            <a href="{{$activity->linkToModel()}}" class="btn btn-primary btn-sm">{{trans('messages.visit')}}</a>
        </div>

    </div>
@endif


@if ($activity->getType() == 'action')
    <div class="activity">
        <div class="header">
            <span class="avatar"><img src="{{$activity->user->avatar()}}" class="rounded-circle"/></span>
            <a href="{{route('users.show', $activity->user)}}">{{$activity->user->name}}</a>
            {{trans('messages.activity_' . $activity->action)}}
            <a href="{{$activity->linkToModel()}}">{{$activity->model->name}}</a>
            {{$activity->created_at->diffForHumans()}}
            {{trans('messages.in')}}
            <a href="{{route('groups.show', $activity->group)}}">{{$activity->group->name}}</a>
        </div>

        <div class="detail">
            {{summary($activity->model->body)}}
        </div>

        <div class="action">
            <a href="{{$activity->linkToModel()}}" class="btn btn-primary btn-sm">{{trans('messages.visit')}}</a>
        </div>

    </div>
@endif

@if ($activity->getType() == 'file')
    <div class="activity">
        <div class="header">
            <span class="avatar"><img src="{{$activity->user->avatar()}}" class="rounded-circle"/></span>
            <a href="{{route('users.show', $activity->user)}}">{{$activity->user->name}}</a>
            {{trans('messages.activity_' . $activity->action)}}
            <a href="{{$activity->linkToModel()}}">{{$activity->model->name}}</a>
            {{$activity->created_at->diffForHumans()}}
            {{trans('messages.in')}}
            <a href="{{route('groups.show', $activity->group)}}">{{$activity->group->name}}</a>
        </div>

        <div class="detail">
            <img src="{{route('groups.files.preview', [$activity->group, $activity->model])}}"/>
        </div>

        <div class="action">
            <a href="{{$activity->linkToModel()}}" class="btn btn-primary btn-sm">{{trans('messages.visit')}}</a>
        </div>

    </div>
@endif


@if ($activity->getType() == 'comment')
    <div class="activity">
        <div class="header">
            <span class="avatar"><img src="{{$activity->user->avatar()}}" class="rounded-circle"/></span>
            <a href="{{route('users.show', $activity->user)}}">{{$activity->user->name}}</a>
            {{trans('messages.activity_' . $activity->action)}}
            <a href="{{$activity->linkToModel()}}">{{$activity->model->discussion->name}}</a>
            {{$activity->created_at->diffForHumans()}}
            {{trans('messages.in')}}
            <a href="{{route('groups.show', $activity->group)}}">{{$activity->group->name}}</a>
        </div>

        <div class="detail">
            {{summary($activity->model->body)}}
        </div>

        <div class="action">
            <a href="{{$activity->linkToModel()}}" class="btn btn-primary btn-sm">{{trans('messages.reply')}}</a>
        </div>
    </div>
@endif
