@extends('dialog')

@section('content')
    <h1>{{ __('Are you really sure you want to delete this groups and all it\'s content?') }}</h1>

    <strong>@lang('You are going to delete this group : ') {{ $group->name }}</strong>
    <p>@lang('Altough this can be undone by an admin please make sure you want this groups to be deleted before clicking')

        {!! Form::model($group, ['method' => 'DELETE', 'action' => ['GroupController@destroy', $group]]) !!}

    <div class="form-group">
        <a class="btn btn-secondary" href="{{ route('groups.show', $group) }}">{{ trans('messages.cancel') }}</a>
        {!! Form::submit(trans('Click here to confirm'), ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@endsection
