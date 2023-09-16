{!! Form::open([
    'action' => ['CommentController@store', $group, $discussion],
    'files' => true,
    'up-target' => '.comments',
    'up-reveal' => '#unread',
    'up-restore-scroll' => 'true',
]) !!}

<div class="form-group">
    {!! Form::textarea('body', null, [
        'class' => 'form-control wysiwyg',
        'data-mention-files' => route('groups.files.mention', $group),
        'data-mention-discussions' => route('groups.discussions.mention', $group),
        'data-mention-users' => route('groups.users.mention', $group),
        'data-mention-users-list' => $group->users->pluck('username'),
    ]) !!}
    <div class="small-help mt-2">
        <i class="fa fa-info-circle"></i> {{ trans('messages.attach_file_help') }}.
        {{ trans('messages.max_file_size') }} : {{ sizeForHumans(config('agorakit.max_file_size') * 1000) }}
    </div>
</div>

<div class="form-group">
    {!! Form::submit(trans('messages.reply'), ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}
