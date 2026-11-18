@extends('app')

@section('content')
    <h2>{{ trans('Add users') }}</h2>

    <p>
        {{ trans('Use this to directly add existing users to the group without invitation. Admin only') }}
    </p>

    {!! Form::open(['action' => ['GroupMassMembershipController@store', $group]]) !!}

    <div class="form-group mb-2">
        {!! Form::label('users', trans('Users to add (existing users only, use the invite feature for new users)')) !!}
        {!! Form::select('users[]', $notmembers, null, ['multiple' => true, 'class' => 'form-control js-tags', 'required', 'id' => 'users']) !!}
    </div>

    <div class="form-group">
        {!! Form::submit(trans('Add user(s)'), ['class' => 'btn btn-primary']) !!}
        <a href="{{ url('/') }}" >{{ trans('Cancel') }}</a>
    </div>

    {!! Form::close() !!}
@endsection
