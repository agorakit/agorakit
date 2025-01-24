@extends('dialog')

@section('content')
        <h1>{{trans('messages.attend_to')}} "<em>{{$participation->action->name}}</em>"?</h1>
        <div class="meta mb-2">
             {{$participation->action->start->format('d/m/Y H:i')}} - {{$participation->action->location}}
        </div>
        <div class="summary mb-4">{{ summary($participation->action->body) }}</div>

        {!! Form::open(['route' => ['groups.actions.participation', $participation->action->group, $participation->action], 'up-target' => '.main']) !!}

        
        {!! Form::label('participation', trans('Participation')) !!}
        {!! Form::select('participation', [10 => __('I will participate'), -10 => __('I will not participate'), 0 => __('I don\'t know yet')], $participation->status, ['class' => 'form-control mb-4']) !!}

        {!! Form::label('notification', trans('Send me a reminder')) !!}
        {!! Form::select('notification', [60 => __('One hour before the event'), 60*24 => __('One day before the event'), 0 => __('No reminder please')], $participation->notification, ['class' => 'form-control']) !!}

        <div class="mt-5 d-flex justify-content-between align-items-center">
            {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary']) !!}
        </div>
        {!! Form::close() !!}
@endsection
