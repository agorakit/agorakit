@extends('app')
@section('content')

    <h1>{{ trans('messages.create_event') }}</h1>

    @if (!$group->exists)
        {!! Form::open(['route' => 'calendarevents.store', 'files' => true]) !!}

        <div class="form-group">
            {!! Form::label('group', trans('messages.group')) !!}
            <select class="form-control" name="group" required="required">
                <option disabled selected value="">{{ trans('messages.choose_a_group') }}</option>
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
        {!! Form::submit(trans('messages.create'), ['class' => 'btn btn-primary']) !!}
    </div>

    @unless ($event->exists)
        {{ Form::hidden('context', Context::get()) }}
    @endunless

    </form>

@endsection
