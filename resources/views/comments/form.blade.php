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
        {!! Form::submit(trans('messages.reply'), ['class' => 'btn btn-primary btn-lg']) !!}
    </div>


    <div>
        <div class="input-group">

            <div class="">
                {{trans('Attach a file')}} :
                {!! Form::file('file', ['class' => 'btn btn-secondary', 'id'=>'file']) !!}
            </div>
        </div>
    </div>


</div>
