@extends('dialog')

@section('content')
    <h1>{{ t('Attend to') }} "<em>{{ $event->name }}</em>"?</h1>
    <div class="meta mb-2">
        {{ $event->start->format('d/m/Y H:i') }} - {{ $event->locationDisplay() }}
    </div>
    <div class="summary mb-4">{{ summary($event->body) }}</div>

    {!! Form::open(['route' => ['groups.calendarevents.attend', $group, $event], 'up-target' => '.main']) !!}


    {!! Form::select(
        'participation',
        [10 => t('I will participate'), -10 => t('I will not participate'), 0 => __('I don\'t know yet')],
        null,
        ['class' => 'form-control mb-4'],
    ) !!}

    <label>{{ t('Send me a reminder') }} :</label>
    {!! Form::select(
        'notification',
        [60 => t('One hour before the event'), 60 * 24 => t('One day before the event'), 0 => t('No reminder please')],
        null,
        ['class' => 'form-control'],
    ) !!}

    <div class="mt-5 d-flex justify-content-between align-items-center">
        {!! Form::submit(t('Save'), ['class' => 'btn btn-primary']) !!}
    </div>
    {!! Form::close() !!}
@endsection
