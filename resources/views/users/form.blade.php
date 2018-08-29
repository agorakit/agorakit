<div class="form-group">
  {!! Form::label('name', trans('messages.name')) !!}
  {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>


<div class="form-group">
  {!! Form::label('username', trans('messages.username')) !!}

  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text" id="basic-addon1">@</span>
    </div>
    {!! Form::text('username', null, ['class' => 'form-control']) !!}
  </div>

</div>

<div class="form-group">
  {!! Form::label('email', trans('messages.email')) !!}
  {!! Form::text('email', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
  {!! Form::label('body', trans('messages.description')) !!}
  {!! Form::textarea('body', null, ['class' => 'wysiwyg form-control']) !!}
</div>


<div class="form-group">
  <label>{{trans('messages.photo')}}</label><br/>
  <input name="cover" id="file" type="file" class="btn btn-primary" title="{{trans('messages.select_one_file')}}">

</div>


<div class="form-group">
  {!! Form::label('address', trans('messages.address') . ':') !!}
  <div class="alert alert-info">
    {{trans('messages.address_privacy_and_help')}}
  </div>
  {!! Form::textarea('address', null, ['class' => 'form-control']) !!}
</div>

@if (Auth::user()->isAdmin())
  <h1>Administration</h1>
  <div class="form-group">
    <div>{{trans('messages.admin')}}</div>
    {{Form::radio('is_admin', 'yes', $user->isAdmin())}} {{trans('messages.yes')}} <br/>
    {{Form::radio('is_admin', 'no', !$user->isAdmin())}} {{trans('messages.no')}} <br/>
  </div>

  <div class="form-group">
    <div>{{trans('messages.email_verified')}}</div>
    {{Form::radio('is_verified', 'yes', $user->isVerified())}} {{trans('messages.yes')}} <br/>
    {{Form::radio('is_verified', 'no', !$user->isVerified())}} {{trans('messages.no')}} <br/>
  </div>

@endif


@include('partials.wysiwyg')
@include('partials.better-file-inputs')
