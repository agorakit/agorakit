@extends('app')

@section('content')
    <h1>{{ trans('messages.modify') }} <strong>"{{ $action->name }}"</strong></h1>

    {!! Form::model($action, ['action' => ['GroupActionController@update', $action->group, $action], 'files' => true]) !!}

    @include('actions.form')

    <div class="form-group">
        {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection
