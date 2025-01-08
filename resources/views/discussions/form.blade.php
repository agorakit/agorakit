<div class="form-group">
    {!! Form::label('name', trans('messages.title')) !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('body', trans('messages.body')) !!}

    @if ($group->exists)
        {!! Form::textarea('body', null, [
            'class' => 'form-control wysiwyg',
            'data-mention-files' => route('groups.files.mention', $group),
            'data-mention-discussions' => route('groups.discussions.mention', $group),
            'data-mention-users' => route('groups.users.mention', $group),
            'data-mention-users-list' => $group->users->pluck('username'),
            'data-group-id' => $group->id,
        ]) !!}
    @else
        {!! Form::textarea('body', null, [
            'class' => 'form-control wysiwyg',
        ]) !!}
    @endif


</div>

<div class="form-group">
    {!! Form::label('file', trans('Attach a file')) !!}
    {!! Form::file('file', ['class' => 'form-control-file', 'id' => 'file']) !!}
</div>

@include('partials.tags_input')
