<div class="form-group">
    {!! Form::label('name', trans('messages.title')) !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    @if (isset($group) && $group->exists)
        {!! Form::label('body', trans('messages.description')) !!}
        {!! Form::textarea('body', null, [
            'class' => 'form-control wysiwyg',
            'data-mention-files' => route('groups.files.mention', $group),
            'data-mention-discussions' => route('groups.discussions.mention', $group),
            'data-mention-users' => route('groups.users.mention', $group),
        ]) !!}
    @else
        {!! Form::label('body', trans('messages.description')) !!}
        {!! Form::textarea('body', null, [
            'class' => 'form-control wysiwyg',
        ]) !!}
    @endif
</div>

<div class="form-group">
    <label for="file">{{ trans('group.cover') }}</label><br />
    <input class="form-control-file" id="file" name="cover" title="{{ trans('messages.select_one_file') }}"
        type="file">
</div>

<div class="form-group">
    <label>{{ trans('messages.visibility') }}</label>
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{ trans('messages.visibility_help') }}
    </div>
    <label class="form-check form-switch" for="is-public">
        <input @if (isset($action) && $action->isPublic()) checked=checked @endif class="form-check-input" id="is-public" name="visibility"
            type="checkbox">
        <span>{{ trans('messages.public') }}</span>
    </label>
</div>

@include('partials.tags_input')

<div class="form-group">
    {!! Form::label('location', trans('messages.location')) !!}
    {!! Form::text('location', null, ['class' => 'form-control', 'rows' => 4]) !!}
</div>

<div class="form-group">
    {!! Form::label('start_date', trans('messages.start_date')) !!}
    {!! Form::date('start_date', $action->start->format('Y-m-d'), ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('start_time', trans('messages.start_time')) !!}
    {!! Form::time('start_time', $action->start->format('H:i'), ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('stop_time', trans('messages.stop_time')) !!}
    @if (isset($action->stop))
        {!! Form::time('stop_time', $action->stop->format('H:i'), ['class' => 'form-control', 'required']) !!}
    @else
        {!! Form::time('stop_time', null, ['class' => 'form-control']) !!}
    @endif
</div>

<div class="form-group">
    {!! Form::label('stop_date', trans('messages.stop_date')) !!}
    @if (isset($action->stop) && $action->stop->format('Y-m-d') != $action->start->format('Y-m-d'))
        {!! Form::date('stop_date', $action->stop->format('Y-m-d'), ['class' => 'form-control']) !!}
    @else
        {!! Form::date('stop_date', null, ['class' => 'form-control']) !!}
    @endif
</div>
