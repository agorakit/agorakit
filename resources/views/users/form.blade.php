<div class="form-group">
    {!! Form::label('name', trans('messages.name')) !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        @lang('Will be shown on your public profile')
    </div>
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>


<div class="form-group">
    {!! Form::label('username', trans('messages.username')) !!}
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
    {!! Form::label('email', trans('messages.email')) !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        @lang('You can change your email at any time, but you will need to verify it')
    </div>
    {!! Form::text('email', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    <div>
        {!! Form::label('password', trans('messages.password')) !!}
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
    {!! Form::label('body', trans('messages.description')) !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        @lang('Will be shown on your public profile')
    </div>
    {!! Form::textarea('body', null, ['class' => 'wysiwyg form-control']) !!}
</div>

@include('partials.tags_input')


<div class="form-group">
    <label>{{trans('messages.photo')}}</label><br/>
    <input name="cover" id="file" type="file" title="{{trans('messages.select_one_file')}}">
</div>


<div class="form-group">
    {!! Form::label('address', trans('messages.address') . ':') !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{trans('messages.address_privacy_and_help')}}
    </div>
    {!! Form::text('address', null, ['class' => 'form-control']) !!}


</div>

@if (Auth::user()->isAdmin())
    <h1>@lang('User administration')</h1>
    <div class="form-group">
        <div>@lang('Is this user super admin ? (use with care)')</div>
        {{Form::radio('is_admin', 'yes', $user->isAdmin())}} {{trans('messages.yes')}} <br/>
        {{Form::radio('is_admin', 'no', !$user->isAdmin())}} {{trans('messages.no')}} <br/>
    </div>

    <div class="form-group">
        <div>{{trans('messages.email_verified')}}</div>
        {{Form::radio('is_verified', 'yes', $user->isVerified())}} {{trans('messages.yes')}} <br/>
        {{Form::radio('is_verified', 'no', !$user->isVerified())}} {{trans('messages.no')}} <br/>
    </div>
@endif
