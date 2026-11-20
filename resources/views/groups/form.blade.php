<div class="form-group">
    {!! Form::label('name', t('Group name')) !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('body', t('Group description')) !!}
    {!! Form::textarea('body', null, ['class' => 'wysiwyg form-control']) !!}
</div>

@include('partials.tags_input')

<div class="form-group">
    <label>{{ t('Cover picture') }}</label><br />
    <input class="form-control-file" id="file" name="cover" title="{{ t('Select one file') }}" type="file">
</div>

<div class="form-group">
    @include('partials.location_input')
</div>

<div class="form-group">
    {!! Form::label('group_type', t('Group type')) !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{ t('Choose the group type you want. An open group has the content public and anyone can join it. A closed group is invite only and the content is only visible to it\'s members. A secret group is not even discoverable by non members.') }}
    </div>
    @if (setting('user_can_create_secret_groups') == 1 || Auth::user()->isAdmin())
        {!! Form::select(
            'group_type',
            ['0' => t('Public group'), '1' => t('Closed group'), '2' => t('Secret group')],
            null,
            ['class' => 'form-control'],
        ) !!}
    @else
        {!! Form::select('group_type', ['0' => t('Public group'), '1' => t('Closed group')], null, [
            'class' => 'form-control',
        ]) !!}
    @endif
</div>

@can('changeGroupStatus', $group)
    <div class="form-group">
        {!! Form::label('status', t('Status')) !!}
        <div class="small-help">
            <i class="fas fa-info-circle"></i>
            {{ t('Choose a status for this group. This allows you to archive an inactive group or on the contrary pin an important group on top of lists') }}
        </div>
        {!! Form::select(
            'status',
            ['0' => t('Normal group'), '10' => t('Pinned'), '-10' => t('Archived')],
            null,
            ['class' => 'form-control'],
        ) !!}
    </div>
@endcan


@if (Auth::user()->isAdmin())
    <div class="form-group">
        {!! Form::checkbox('pinned_navbar', 'yes', $group->getSetting('pinned_navbar', false)) !!}
        <label class="humble" for="pinned_navbar">
            {!! t('Display this group into the navigation bar. It won\'t be displayed into my groups anymore.') !!}
        </label>
    </div>
@endif
