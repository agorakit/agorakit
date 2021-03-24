{!! Form::open(['action' => ['CommentController@store', $group, $discussion], 'files' => true, 'up-target' =>
'.comments', 'up-reveal' => '#unread', 'up-restore-scroll' => 'true']) !!}

<div class="form-group">
    {!! Form::textarea('body', null, [
    'class' => 'form-control wysiwyg' ,
    'data-mention-files' => route('groups.files.mention', $group),
    'data-mention-discussions' => route('groups.discussions.mention', $group),
    'data-mention-users' => route('groups.users.mention', $group)
    ]
    ) !!}
</div>



<div class="flex justify-between">

    <div class="form-group">
        {!! Form::submit(trans('messages.reply'), ['class' => 'btn btn-primary']) !!}
    </div>

    <div class="form-group">
        <label for="attachment">{{trans('Attach a file')}}</label>
        <br />
        <input type="file" name="files[]" id="attachment" multiple="multiple">
    </div>

</div>




{!! Form::close() !!}