@extends('app')

@section('content')
    <h1>{{ trans('Modify') }} <strong>"{{ $event->name }}"</strong></h1>

    {!! Form::model($event, ['action' => ['GroupCalendarEventController@update', $event->group, $event], 'files' => true]) !!}

    @include('calendarevents.form')

    <div class="form-group">
        {!! Form::submit(trans('Save'), ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection
