@extends('dialog')

@section('content')
        <h1>{{trans('messages.attend_to')}} "<em>{{$participation->calendarevent->name}}</em>"?</h1>
        <div class="meta mb-2">
             {{$participation->calendarevent->start->format('d/m/Y H:i')}} - {{$participation->calendarevent->location}}
        </div>
        <div class="summary mb-4">{{ summary($participation->calendarevent->body) }}</div>

        {!! Form::open(['route' => ['groups.calendarevents.participation', $participation->calendarevent->group, $participation->calendarevent], 'up-target' => '.main']) !!}

        
        {!! Form::select('participation', [10 => __('I will participate'), -10 => __('I will not participate'), 0 => __('I don\'t know yet')], $participation->status, ['class' => 'form-control mb-4']) !!}

        <label>{{__('Send me a reminder') }} :</label>
        {!! Form::select('notification', [60 => __('One hour before the event'), 60*24 => __('One day before the event'), 0 => __('No reminder please')], $participation->notification, ['class' => 'form-control']) !!}

        <div class="mt-5 d-flex justify-content-between align-items-center">
            {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary']) !!}
        </div>
        {!! Form::close() !!}
@endsection
