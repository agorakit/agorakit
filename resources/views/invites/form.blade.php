@extends('group')

@section('content')
    <h2>{{ trans('membership.invite_title') }}</h2>

    <p>
        {{ trans('membership.invite_intro') }}

    </p>

    {!! Form::open(['action' => ['InviteController@sendInvites', $group]]) !!}

    <div class="form-group">
        {!! Form::label('invitations', trans('membership.people_to_invite')) !!}
        {!! Form::textarea('invitations', null, ['class' => 'form-control', 'required']) !!}
    </div>

    <div class="form-group">
        {!! Form::submit(trans('membership.invite_button'), ['class' => 'btn btn-primary']) !!}
        <a href="{{ url('/') }}" >{{ trans('messages.cancel') }}</a>
    </div>

    {!! Form::close() !!}

    </div>
@endsection
