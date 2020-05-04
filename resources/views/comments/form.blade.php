<div class="form-group">
    {!! Form::textarea('body', null, [
        'class' => 'form-control wysiwyg' ,
        'data-mention-files' => route('groups.files.mention', $group),
        'data-mention-discussions' => route('groups.discussions.mention', $group),
        'data-mention-users' => route('groups.users.mention', $group)
    ]
    ) !!}
</div>


<div class="d-flex justify-content-between">
    <div class="form-group">
        {!! Form::submit(trans('messages.reply'), ['class' => 'btn btn-primary']) !!}
    </div>


    <div class="ml-2">
        <div class="custom-file">
            <input type="file" name="file" class="custom-file-input" id="customFileLangHTML">
            <label class="custom-file-label" for="customFileLangHTML" data-browse="@lang('Browse')">{{trans('Attach a file')}}</label>
        </div>
    </div>


</div>
