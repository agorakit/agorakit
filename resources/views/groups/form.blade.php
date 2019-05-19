<div class="form-group">
    {!! Form::label('name', trans('group.name')) !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('body', trans('group.description')) !!}
    {!! Form::textarea('body', null, ['class' => 'wysiwyg form-control', 'required']) !!}
</div>

@include('partials.tags_form')

<div class="form-group">
    <label>{{trans('group.cover')}}</label><br/>
    <input name="cover" id="file" type="file" class="form-control-file" title="{{trans('messages.select_one_file')}}">
</div>

<div class="form-group">
    {!! Form::label('address', trans('messages.address') . ':') !!}
    <div class="alert alert-info">
        {{trans('messages.address_privacy_and_help')}}
    </div>
    {!! Form::textarea('address', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('group_type', trans('group.type')) !!}
    @if (setting('user_can_create_secret_groups') == 1 || Auth::user()->isAdmin())
        {!! Form::select('group_type', ['0' => trans('group.open'), '1' => trans('group.closed'), '2' => trans('group.secret')], null, ['class' => 'form-control']) !!}
    @else
        {!! Form::select('group_type', ['0' => trans('group.open'), '1' => trans('group.closed')], null, ['class' => 'form-control']) !!}
    @endif
</div>




@include('partials.wysiwyg')
