@extends('app')

@section('content')

    <div class="dialog">
        <h5>{{trans('messages.attend_to')}} "{{$action->name}}"</h5>
        <div class="meta mb-2">
            {{$action->start->format('H:i')}} - {{$action->location}}
        </div>
        <div class="summary mb-4">{{ summary($action->body) }}</div>

        {!! Form::open(['route' => ['groups.actions.attend', $group, $action]]) !!}
        {!! Form::submit(trans('messages.attend'), ['class' => 'btn btn-primary']) !!}
        <a href="{{ route('groups.actions.show', [$group, $action]) }}" up-close class="ml-4">{{trans('messages.cancel')}}</a>
        {!! Form::close() !!}
    </div>


@endsection
