@extends('dialog')

@section('content')
    <h1>{{ trans('messages.unattend') }} "{{ $action->name }}"</h1>
    <div class="meta mb-2">
        {{ $action->start->format('d/m/Y H:i') }} - {{ $action->location }}
    </div>
    <div class="summary mb-4">{{ summary($action->body) }}</div>

    <div class="mt-5 d-flex justify-content-between align-items-center">
        {!! Form::open(['route' => ['groups.actions.unattend', $group, $action], 'up-target' => '.main']) !!}
        {!! Form::submit(trans('messages.unattend'), ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </div>
@endsection
