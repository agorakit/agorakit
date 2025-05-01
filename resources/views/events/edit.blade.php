@extends('group')

@section('content')
    <h1>{{ trans('messages.modify') }} <strong>"{{ $event->name }}"</strong></h1>

    {!! Form::model($event, ['action' => ['GroupEventController@update', $event->group, $event], 'files' => true]) !!}

    @include('events.form')

    <div class="form-group">
        {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection
