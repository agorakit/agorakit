@extends('app')

@section('content')
    <h2>{{ trans('Invite people to this group') }}</h2>

    <p>
        {{ trans('Invite people who might be interested in this group. Please don\'t spam people\'s emails') }}

    </p>

    {!! Form::open(['action' => ['InviteController@sendInvites', $group]]) !!}

    <div class="form-group">
        {!! Form::label('invitations', trans('People to invite')) !!}
        {!! Form::textarea('invitations', null, ['class' => 'form-control', 'required']) !!}
    </div>

    <div class="form-group">
        {!! Form::submit(trans('Invite'), ['class' => 'btn btn-primary']) !!}
        <a href="{{ url('/') }}" >{{ trans('Cancel') }}</a>
    </div>

    {!! Form::close() !!}

    </div>
@endsection
