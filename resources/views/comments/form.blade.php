<div class="form-group">
    {!! Form::textarea('body', null, [
        'class' => 'form-control wysiwyg' ,
        'required',
        'data-mention-files' => route('groups.files.mention', $group),
        'data-mention-discussions' => route('groups.discussions.mention', $group),
        'data-mention-users' => route('groups.users.mention', $group)
    ]
    ) !!}
</div>

<div class="form-group">
    <label for="file">{{trans('Attach a file')}}</label>
    {!! Form::file('file', ['class' => 'form-control-file', 'id'=>'file']) !!}
</div>
