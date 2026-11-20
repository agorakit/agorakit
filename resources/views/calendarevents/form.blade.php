<div class="form-group">
    {!! Form::label('name', t('Title')) !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    @if (isset($group) && $group->exists)
        {!! Form::label('body', t('Description')) !!}
        {!! Form::textarea('body', null, [
            'class' => 'form-control wysiwyg',
            'data-mention-files' => route('groups.files.mention', $group),
            'data-mention-discussions' => route('groups.discussions.mention', $group),
            'data-mention-users' => route('groups.users.mention', $group),
        ]) !!}
    @else
        {!! Form::label('body', t('Description')) !!}
        {!! Form::textarea('body', null, [
            'class' => 'form-control wysiwyg',
        ]) !!}
    @endif
</div>

<div class="form-group">
    <label for="file">{{ t('Cover picture') }}</label><br />
    <input class="form-control-file" id="file" name="cover" title="{{ t('Select one file') }}"
        type="file">
</div>

<div class="form-group">
    <label>{{ t('Visibility') }}</label>
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{ t('You can make this element public even if your group is private. In this case everyone will be able to see this element without being member.') }}
    </div>
    <label class="form-check form-switch" for="is-public">
        <input @if (isset($event) && $event->isPublic()) checked=checked @endif class="form-check-input" id="is-public" name="visibility"
            type="checkbox">
        <span>{{ t('Public') }}</span>
    </label>
</div>

@include('partials.tags_input')

<div class="form-group">
@include('partials.location_input')
</div>

<div class="form-group">
    {!! Form::label('start_date', t('Start date')) !!}
    {!! Form::date('start_date', $event->start->format('Y-m-d'), ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('start_time', t('Start time')) !!}
    {!! Form::time('start_time', $event->start->format('H:i'), ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('stop_time', t('Stop time')) !!}
    @if (isset($event->stop))
        {!! Form::time('stop_time', $event->stop->format('H:i'), ['class' => 'form-control', 'required']) !!}
    @else
        {!! Form::time('stop_time', null, ['class' => 'form-control']) !!}
    @endif
</div>

<div class="form-group">
    {!! Form::label('stop_date', t('Stop date')) !!}
    @if (isset($event->stop) && $event->stop->format('Y-m-d') != $event->start->format('Y-m-d'))
        {!! Form::date('stop_date', $event->stop->format('Y-m-d'), ['class' => 'form-control']) !!}
    @else
        {!! Form::date('stop_date', null, ['class' => 'form-control']) !!}
    @endif
</div>

<div class="form-group">
    {!! Form::label('registration_closes_at', t('Registration closes at')) !!}
    {!! Form::date('registration_closes_at', $event->hasRegistrationClosesAt() ? $event->registration_closes_at->format('Y-m-d') : null, ['class' => 'form-control']) !!}
</div>
