@extends('group')
@section('content')

    <h1>{{ trans('action.create_one_button') }}</h1>

    @if (!$group->exists)
        {!! Form::open(['route' => 'actions.store', 'files' => true]) !!}

        <div class="form-group">
            {!! Form::label('group', trans('messages.group')) !!}
            <select class="form-control" id="group" name="group" required="required">
                <option value="" disabled selected>{{ trans('messages.choose_a_group') }}</option>
                @foreach (Auth::user()->groups as $user_group)
                    <option value="{{ $user_group->id }}">{{ $user_group->name }}</option>
                @endforeach
            </select>
        </div>

        @include('actions.form')

        <div class="form-group">
            {!! Form::submit(trans('messages.create'), ['class' => 'btn btn-primary']) !!}
        </div>

        {!! Form::close() !!}
    @else
        {!! Form::model($action, ['action' => ['GroupActionController@store', $group], 'files' => true]) !!}

        @include('actions.form')

        <div class="form-group">
            {!! Form::submit(trans('messages.create'), ['class' => 'btn btn-primary']) !!}
        </div>

        {!! Form::close() !!}
    @endif

    </div>

@endsection
