@if ($activity->getType() == 'discussion')
    <div class="activity">
        <div class="header">
            <span class="avatar"><img alt="" src="{{{{route('users.cover', [$activity->user, 'small'])}}}}" class="rounded-full"/></span>
            <a  href="{{route('users.show', $activity->user)}}">{{$activity->user->name}}</a>
            {{t('messages.activity_' . $activity->action)}}
            <a  href="{{$activity->linkToModel()}}">{{$activity->model->name}}</a>
            {{$activity->created_at->diffForHumans()}}
            {{t('messages.in')}}
            <a  href="{{route('groups.show', $activity->group)}}">{{$activity->group->name}}</a>
        </div>

        <div class="detail">
            {{summary($activity->model->body)}}
        </div>

        <div class="action">
            <a  href="{{$activity->linkToModel()}}" class="btn btn-primary btn-sm">{{t('Visit')}}</a>
        </div>

    </div>
@endif


@if ($activity->getType() == 'action')
    <div class="activity">
        <div class="header">
            <span class="avatar"><img alt="" src="{{{{route('users.cover', [$activity->user, 'small'])}}}}" class="rounded-full"/></span>
            <a  href="{{route('users.show', $activity->user)}}">{{$activity->user->name}}</a>
            {{t('messages.activity_' . $activity->action)}}
            <a  href="{{$activity->linkToModel()}}">{{$activity->model->name}}</a>
            {{$activity->created_at->diffForHumans()}}
            {{t('messages.in')}}
            <a  href="{{route('groups.show', $activity->group)}}">{{$activity->group->name}}</a>
        </div>

        <div class="detail">
            {{summary($activity->model->body)}}
        </div>

        <div class="action">
            <a  href="{{$activity->linkToModel()}}" class="btn btn-primary btn-sm">{{t('Visit')}}</a>
        </div>

    </div>
@endif

@if ($activity->getType() == 'file')
    <div class="activity">
        <div class="header">
            <span class="avatar"><img alt="" src="{{{{route('users.cover', [$activity->user, 'small'])}}}}" class="rounded-full"/></span>
            <a  href="{{route('users.show', $activity->user)}}">{{$activity->user->name}}</a>
            {{t('messages.activity_' . $activity->action)}}
            <a  href="{{$activity->linkToModel()}}">{{$activity->model->name}}</a>
            {{$activity->created_at->diffForHumans()}}
            {{t('messages.in')}}
            <a  href="{{route('groups.show', $activity->group)}}">{{$activity->group->name}}</a>
        </div>

        <div class="detail">
            <img alt="" src="{{route('groups.files.preview', [$activity->group, $activity->model])}}"/>
        </div>

        <div class="action">
            <a  href="{{$activity->linkToModel()}}" class="btn btn-primary btn-sm">{{t('Visit')}}</a>
        </div>

    </div>
@endif


@if ($activity->getType() == 'comment')
    <div class="activity">
        <div class="header">
            <span class="avatar"><img alt="" src="{{{{route('users.cover', [$activity->user, 'small'])}}}}" class="rounded-full"/></span>
            <a  href="{{route('users.show', $activity->user)}}">{{$activity->user->name}}</a>
            {{t('messages.activity_' . $activity->action)}}
            <a  href="{{$activity->linkToModel()}}">{{$activity->model->discussion->name}}</a>
            {{$activity->created_at->diffForHumans()}}
            {{t('messages.in')}}
            <a  href="{{route('groups.show', $activity->group)}}">{{$activity->group->name}}</a>
        </div>

        <div class="detail">
            {{summary($activity->model->body)}}
        </div>

        <div class="action">
            <a  href="{{$activity->linkToModel()}}" class="btn btn-primary btn-sm">{{t('Reply')}}</a>
        </div>
    </div>
@endif
