@extends('app')

@section('content')
    <h1>{{ trans('group.import_title') }}</h1>

    @if ($existing_group)<div class="alert text-bg-danger"><p>Warning: {{ $existing_group }}</p></div>@endif

    <p>{{ trans('group.import_blob') }}</p>

    {!! Form::open(['action' => ['GroupController@import', 'files' => false]], null) !!}
    @csrf
    @honeypot
    <input type="hidden" name="user_id" value="{{ $user_id }}">
    <input type="hidden" name="import_basename" value="{{ $import_basename }}">

    @if ($existing_usernames)<p>{{ trans('group.existing_usernames_help') }}</p>@endif
    @foreach ($existing_usernames as $id => $existing_username)<div class="form-group">
        {!! Form::label('new_username_'.$id, trans('new username')) !!}
        {!! Form::text('new_username_'.$id, $existing_username, ['class' => 'form-control', 'required']) !!}
    </div>@endforeach

    <div class="form-group">
        {!! Form::submit(trans('group.create_button'), ['class' => 'btn btn-primary']) !!}
        <a class="js-back" href="#" up-dismiss>{{ trans('messages.cancel') }}</a>
    </div>

    {!! Form::close() !!}
@endsection
