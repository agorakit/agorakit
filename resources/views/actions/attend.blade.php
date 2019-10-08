@extends('dialog')

@section('content')
        <h1>{{trans('messages.attend_to')}} "<em>{{$action->name}}</em>"?</h1>
        <div class="meta mb-2">
            {{$action->start->format('H:i')}} - {{$action->location}}
        </div>
        <div class="summary mb-4">{{ summary($action->body) }}</div>

        {!! Form::open(['route' => ['groups.actions.attend', $group, $action]]) !!}

        <div class="mt-5 d-flex justify-content-between align-items-center">
            {!! Form::submit(trans('messages.attend'), ['class' => 'btn btn-primary']) !!}
        </div>
        {!! Form::close() !!}
@endsection
