@extends('group')

@section('content')
    <h1 class="mb-3">{{ trans('Configuration') }}</h1>

    {!! Form::model($group, ['action' => ['GroupController@update', $group], 'files' => true]) !!}

    @include('groups.form')

    <div class="form-group">
        {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection
