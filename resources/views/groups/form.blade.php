<div class="form-group">
    {!! Form::label('name', trans('group.name')) !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('body', trans('group.description')) !!}
    {!! Form::textarea('body', null, ['class' => 'wysiwyg form-control']) !!}
</div>

@include('partials.tags_input')

<div class="form-group">
    <label>{{trans('group.cover')}}</label><br/>
    <input name="cover" id="file" type="file" class="form-control-file" title="{{trans('messages.select_one_file')}}">
</div>

<div class="form-group">
@include('traits.location_input')
</div>

<div class="form-group">
    {!! Form::label('group_type', trans('group.type')) !!}
     <div class="small-help">
            <i class="fas fa-info-circle"></i>
            {{trans('group.group_type_help')}}
        </div>
    @if (setting('user_can_create_secret_groups') == 1 || Auth::user()->isAdmin())
        {!! Form::select('group_type', ['0' => trans('group.open'), '1' => trans('group.closed'), '2' => trans('group.secret')], null, ['class' => 'form-control']) !!}
    @else
        {!! Form::select('group_type', ['0' => trans('group.open'), '1' => trans('group.closed')], null, ['class' => 'form-control']) !!}
    @endif
</div>

@can('changeGroupStatus', $group)
    <div class="form-group">
        {!! Form::label('status', trans('group.status')) !!}
         <div class="small-help">
            <i class="fas fa-info-circle"></i>
            {{trans('group.status_help')}}
        </div>
        {!! Form::select('status', ['0' => trans('group.normal'), '10' => trans('group.pinned'), '-10' => trans('group.archived')], null, ['class' => 'form-control']) !!}
    </div>
@endcan


@if(Auth::user()->isAdmin())
    <div class="form-group">
        {!! Form::checkbox('pinned_navbar', 'yes', $group->getSetting('pinned_navbar', false)) !!}
        <label class="humble" for="pinned_navbar">
          {!! trans('group.navbar', ['my_groups' => trans('messages.my_groups')]) !!}
        </label>
    </div>
@endif