@extends('app')

@section('content')


    <h1>{{trans('messages.unattend')}} "{{$action->name}}"</h1>
    <div class="meta mb-2">
        {{$action->start->format('H:i')}} - {{$action->location}}
    </div>
    <div class="summary mb-4">{{ summary($action->body) }}</div>

    <div class="d-flex justify-content-end">
        {!! Form::open(['route' => ['groups.actions.unattend', $group, $action]]) !!}
        {!! Form::submit(trans('messages.unattend'), ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </div>


@endsection
