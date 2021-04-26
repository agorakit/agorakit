<div class="form-group">
    {!! Form::textarea('body', null, [
    'class' => 'form-control wysiwyg' ,
    'data-mention-files' => route('groups.files.mention', $group),
    'data-mention-discussions' => route('groups.discussions.mention', $group),
    'data-mention-users' => route('groups.users.mention', $group),
    'data-mention-users-list' => $group->users->pluck('username')
    ]
    ) !!}
</div>





<div class="form-group">
    <label for="attachment">{{trans('Attach a file')}}</label>
    <br />
    <input type="file" name="files[]" id="attachment" multiple="multiple">
</div>