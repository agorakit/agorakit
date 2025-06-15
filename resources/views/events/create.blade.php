@extends('app')
@section('content')

    <h1>{{ trans('messages.create_event') }}</h1>

    @if (!$group->exists)
        {!! Form::open(['route' => 'events.store', 'files' => true]) !!}

        <div class="form-group">
            {!! Form::label('group', trans('messages.group')) !!}
            <select class="form-control" name="group" required="required">
                <option disabled selected value="">{{ trans('messages.choose_a_group') }}</option>
                @foreach (Auth::user()->groups as $user_group)
                    <option value="{{ $user_group->id }}">{{ $user_group->name }}</option>
                @endforeach
            </select>
        </div>

        @include('events.form')

        <div class="form-group">
            {!! Form::submit(trans('messages.create'), ['class' => 'btn btn-primary']) !!}
        </div>

        {!! Form::close() !!}
    @else
        {!! Form::model($event, ['action' => ['GroupEventController@store', $group], 'files' => true]) !!}

        @include('events.form')

        <div class="form-group">
            {!! Form::submit(trans('messages.create'), ['class' => 'btn btn-primary']) !!}
        </div>

        {!! Form::close() !!}
    @endif

    </div>

@endsection
