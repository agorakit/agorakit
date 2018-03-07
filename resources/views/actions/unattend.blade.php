@extends('app')

@section('content')

    <div class="dialog">
        <h5>{{trans('messages.unattend')}} "{{$action->name}}"</h5>
        <div class="meta mb-2">
            {{$action->start->format('H:i')}} - {{$action->location}}
        </div>
        <div class="summary mb-4">{{ summary($action->body) }}</div>

        {!! Form::open(['route' => ['groups.actions.unattend', $group, $action]]) !!}
        {!! Form::submit(trans('messages.unattend'), ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </div>

@endsection
