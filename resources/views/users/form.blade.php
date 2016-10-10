<div class="form-group">
    {!! Form::label('name', 'Nom:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>


<div class="form-group">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::text('email', null, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('body', 'Bio:') !!}
    {!! Form::textarea('body', null, ['id' => 'wysiwyg', 'class' => 'form-control', 'required']) !!}
</div>


<div class="form-group">
    {!! Form::label('cover', 'Photo:') !!}
    {!! Form::file('cover', null, ['class' => 'form-control']) !!}
</div>


<div class="form-group">
    {!! Form::label('address', trans('messages.address') . ':') !!}
    {!! Form::textarea('address', null, ['class' => 'form-control']) !!}
</div>

@if (Auth::user()->isAdmin())
    <div class="form-group">
        {{Form::radio('is_admin', 'yes', $user->isAdmin())}} {{trans('messages.admin')}} <br/>
        {{Form::radio('is_admin', 'no', !$user->isAdmin())}} {{trans('messages.not_an_admin')}} <br/>
    </div>
@endif


@include('partials.wysiwyg')
