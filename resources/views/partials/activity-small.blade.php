@if ($activity->getType() == 'discussion')
    <div class="activity-small">
        <span class="avatar"><img src="{{$activity->user->avatar()}}" class="img-circle"/></span>
        <a href="{{action('UserController@show', $activity->user)}}">{{$activity->user->name}}</a>
        {{trans('messages.activity_' . $activity->action)}}
        <a href="{{$activity->linkToModel()}}">{{$activity->model->name}}</a>
        {{$activity->created_at->diffForHumans()}}
        {{trans('messages.in')}}
        <a href="{{action('GroupController@show', $activity->group)}}">{{$activity->group->name}}</a>
    </div>
@endif


@if ($activity->getType() == 'action')
    <div class="activity-small">
        <span class="avatar"><img src="{{$activity->user->avatar()}}" class="img-circle"/></span>
        <a href="{{action('UserController@show', $activity->user)}}">{{$activity->user->name}}</a>
        {{trans('messages.activity_' . $activity->action)}}
        <a href="{{$activity->linkToModel()}}">{{$activity->model->name}}</a>
        {{$activity->created_at->diffForHumans()}}
        {{trans('messages.in')}}
        <a href="{{action('GroupController@show', $activity->group)}}">{{$activity->group->name}}</a>
    </div>
@endif

@if ($activity->getType() == 'file')
    <div class="activity-small">
        <span class="avatar"><img src="{{$activity->user->avatar()}}" class="img-circle"/></span>
        <a href="{{action('UserController@show', $activity->user)}}">{{$activity->user->name}}</a>
        {{trans('messages.activity_' . $activity->action)}}
        <a href="{{$activity->linkToModel()}}">{{$activity->model->name}}</a>
        {{$activity->created_at->diffForHumans()}}
        {{trans('messages.in')}}
        <a href="{{action('GroupController@show', $activity->group)}}">{{$activity->group->name}}</a>
    </div>
@endif


@if ($activity->getType() == 'comment')
    <div class="activity-small">
        <span class="avatar"><img src="{{$activity->user->avatar()}}" class="img-circle"/></span>
        <a href="{{action('UserController@show', $activity->user)}}">{{$activity->user->name}}</a>
        {{trans('messages.activity_' . $activity->action)}}
        <a href="{{$activity->linkToModel()}}">{{$activity->model->discussion->name}}</a>
        {{$activity->created_at->diffForHumans()}}
        {{trans('messages.in')}}
        <a href="{{action('GroupController@show', $activity->group)}}">{{$activity->group->name}}</a>
    </div>
@endif
