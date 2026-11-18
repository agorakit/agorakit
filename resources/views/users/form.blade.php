<div class="form-group">
    {!! Form::label('name', trans('Name')) !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        @lang('Will be shown on your public profile')
    </div>
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>


<div class="form-group">
    {!! Form::label('username', trans('username')) !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        @lang('User name is used to @mention you')
    </div>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">@</span>
        </div>
        {!! Form::text('username', null, ['class' => 'form-control']) !!}
    </div>

</div>

<div class="form-group">
    {!! Form::label('email', trans('Email')) !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        @lang('You can change your email at any time, but you will need to verify it')
    </div>
    {!! Form::text('email', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    <div>
        {!! Form::label('password', trans('Password')) !!}
        <div class="small-help">
            <i class="fas fa-info-circle"></i>
            @lang('Use this to set your password or to change it. Minimum 8 characters.')
        </div>
        {!! Form::password('password', ['class' => 'form-control', 'minlength'=> '8', 'autocomplete'=>'new-password']) !!}
    </div>

    <div class="mt-2">
        {!! Form::label('password_confirmation', trans('Confirm your password')) !!}
        <div class="small-help">
            <i class="fas fa-info-circle"></i>
            @lang('Re-type your password')
        </div>
        {!! Form::password('password_confirmation', ['class' => 'form-control', 'autocomplete'=>'new-password']) !!}
    </div>

</div>



<div class="form-group">
    {!! Form::label('body', trans('Description')) !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        @lang('Will be shown on your public profile')
    </div>
    {!! Form::textarea('body', null, ['class' => 'wysiwyg form-control']) !!}
</div>

@include('partials.tags_input')


<div class="form-group">
    <label>{{trans('Photo')}}</label><br/>
    <input name="cover" id="file" type="file" title="{{trans('Select one file')}}">
</div>


<div class="form-group">
@include('partials.location_input')
</div>

@if (Auth::user()->isAdmin())
    <h1>@lang('User administration')</h1>
    <div class="form-group">
        <div>@lang('Is this user super admin ? (use with care)')</div>
        {{Form::radio('is_admin', 'yes', $user->isAdmin())}} {{trans('yes')}} <br/>
        {{Form::radio('is_admin', 'no', !$user->isAdmin())}} {{trans('no')}} <br/>
    </div>

    <div class="form-group">
        <div>{{trans('Email verified')}}</div>
        {{Form::radio('is_verified', 'yes', $user->isVerified())}} {{trans('yes')}} <br/>
        {{Form::radio('is_verified', 'no', !$user->isVerified())}} {{trans('no')}} <br/>
    </div>

     <div class="form-group">
        <div>{{trans('Is this user banned?')}}</div>
        {{Form::radio('is_user_banned', 'yes', $user->isBanned())}} {{trans('yes')}} <br/>
        {{Form::radio('is_user_banned', 'no', !$user->isBanned())}} {{trans('no')}} <br/>
    </div>
@endif
