@extends('app')
@section('content')

    <h1>{{ t('Add an event') }}</h1>

    @if (!$group->exists)
        {!! Form::open(['route' => 'calendarevents.store', 'files' => true]) !!}

        <div class="form-group">
            {!! Form::label('group', t('Group')) !!}
            <select class="form-control" name="group" required="required">
                <option disabled selected value="">{{ t('Choose a group') }}</option>
                @foreach (Auth::user()->groups as $user_group)
                    <option value="{{ $user_group->id }}">{{ $user_group->name }}</option>
                @endforeach
            </select>
        </div>

        @include('calendarevents.form')
    @else
        <form action="{{ route('groups.calendarevents.store', $group) }}" enctype="multipart/form-data" method="post">
            @include('calendarevents.form')
    @endif

    <div class="form-group">
        {!! Form::submit(t('Create'), ['class' => 'btn btn-primary']) !!}
    </div>

    @unless ($event->exists)
        {{ Form::hidden('context', Context::get()) }}
    @endunless

    </form>

@endsection
