@extends('app')

@section('content')
    <h2>{{ t('Invite people to this group') }}</h2>

    <p>
        {{ t('Invite people who might be interested in this group. Please don\'t spam people\'s emails') }}

    </p>

    {!! Form::open(['action' => ['InviteController@sendInvites', $group]]) !!}

    <div class="form-group">
        {!! Form::label('invitations', t('People to invite')) !!}
        {!! Form::textarea('invitations', null, ['class' => 'form-control', 'required']) !!}
    </div>

    <div class="form-group">
        {!! Form::submit(t('Invite'), ['class' => 'btn btn-primary']) !!}
        <a href="{{ url('/') }}" >{{ t('Cancel') }}</a>
    </div>

    {!! Form::close() !!}

    </div>
@endsection
