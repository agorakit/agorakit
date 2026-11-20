{!! Form::open([
    'action' => ['CommentController@store', $group, $discussion],
    'files' => true,
    'up-target' => '.comments',
    'up-scroll' => '#unread',
]) !!}

<div class="form-group">
    {!! Form::textarea('body', null, [
        'class' => 'form-control wysiwyg',
        'data-mention-files' => route('groups.files.mention', $group),
        'data-mention-discussions' => route('groups.discussions.mention', $group),
        'data-mention-users' => route('groups.users.mention', $group),
        'data-mention-users-list' => $group->users->pluck('username'),
        'data-group-id' => $group->id,
    ]) !!}
    <div class="small-help mt-2">
        <i class="fa fa-info-circle"></i> {{ t('Simply drop a file on the editor to add it or use the image button in the toolbar') }}.
        {{ t('Maximum file size') }} : {{ sizeForHumans(config('agorakit.max_file_size') * 1000) }}
    </div>
</div>

<div class="form-group">
    {!! Form::submit(t('Reply'), ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}
