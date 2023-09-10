@extends('group')

@section('content')
    <h1>{{ trans('group.create_group_title') }}</h1>

    {!! Form::open(['action' => ['GroupController@store'], 'files' => true], null) !!}

    @include('groups.form')

    <div class="form-group">
        {!! Form::submit(trans('group.create_button'), ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection
