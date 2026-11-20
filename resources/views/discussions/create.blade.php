@extends('app')

@section('content')

    <h1>{{ t('Start a new discussion') }}</h1>

    @if (!$group->exists)
        {!! Form::open(['route' => 'discussions.store', 'files' => true]) !!}

        <div class="form-group">
            {!! Form::label('group', t('Group')) !!}
            <select class="form-control" name="group" required="required">
                <option disabled selected value="">{{ t('Choose a group') }}</option>
                @foreach (Auth::user()->groups as $user_group)
                    <option value="{{ $user_group->id }}">{{ $user_group->name }}</option>
                @endforeach
            </select>
        </div>

        @include('discussions.form')

        <div class="form-group">
            {!! Form::submit(t('Create'), ['class' => 'btn btn-primary']) !!}
        </div>

        {!! Form::close() !!}
    @else
        <form action="{{ route('groups.discussions.store', $group) }}" enctype="multipart/form-data" method="post">
            @csrf

            @include('discussions.form')

            <div class="form-group">
                <input class="btn btn-primary" name="{{ t('Create') }}" type="submit" />
            </div>

        </form>
    @endif

@endsection
