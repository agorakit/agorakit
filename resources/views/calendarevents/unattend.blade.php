@extends('dialog')

@section('content')
    <h1>{{ trans('messages.unattend') }} "{{ $event->name }}"</h1>
    <div class="meta mb-2">
        {{ $event->start->format('d/m/Y H:i') }} - {{ $event->locationDisplay() }}
    </div>
    <div class="summary mb-4">{{ summary($event->body) }}</div>

    <div class="mt-5 d-flex justify-content-between align-items-center">
        {!! Form::open(['route' => ['groups.calendarevents.unattend', $group, $event], 'up-target' => '.main']) !!}
        {!! Form::submit(trans('messages.unattend'), ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </div>
@endsection
