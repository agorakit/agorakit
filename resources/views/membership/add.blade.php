@extends('group')

@section('content')
    <h2>{{ trans('membership.add_users') }}</h2>

    <p>
        {{ trans('membership.add_users_intro') }}
    </p>

    {!! Form::open(['action' => ['GroupMassMembershipController@store', $group]]) !!}

    <div class="form-group mb-2">
        {!! Form::label('users', trans('membership.users_to_add')) !!}
        {!! Form::select('users[]', $notmembers, null, ['multiple' => true, 'class' => 'form-control js-tags', 'required', 'id' => 'users']) !!}
    </div>

    <div class="form-group">
        {!! Form::submit(trans('membership.add_button'), ['class' => 'btn btn-primary']) !!}
        <a href="{{ url('/') }}" >{{ trans('messages.cancel') }}</a>
    </div>

    {!! Form::close() !!}
@endsection
