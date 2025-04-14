@extends('dialog')

@section('content')
    <h1>{{ trans('messages.create_folder') }}</h1>

    <p>{{ trans('messages.create_folder_help') }}</p>

    {!! Form::open(['url' => route('files.createfolder', ['group' => $group, 'parent' => $parent])]) !!}

    <div class="form-group">
        <label for="name">{{ trans('messages.name') }}</label>
        <input class="form-control" name="name" type="text" value="{{ old('name') }}" />

    </div>

    <div class="d-flex justify-content-between">
        <input class="btn btn-primary" type="submit" value="{{ trans('messages.create') }}" />
        <a class="js-back" href="#" up-dismiss>{{ trans('messages.cancel') }}</a>
    </div>

    {!! Form::close() !!}
@endsection
