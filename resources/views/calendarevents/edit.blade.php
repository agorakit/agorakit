@extends('app')

@section('content')
    <h1>{{ t('Modify') }} <strong>"{{ $event->name }}"</strong></h1>

    {!! Form::model($event, ['action' => ['GroupCalendarEventController@update', $event->group, $event], 'files' => true]) !!}

    @include('calendarevents.form')

    <div class="form-group">
        {!! Form::submit(t('Save'), ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection
