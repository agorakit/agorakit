@extends('dialog')

@section('content')
    <h1>{{ t('Create a folder') }}</h1>

    <p>{{ t('Enter the name of the folder') }}</p>

    {!! Form::open(['url' => route('groups.files.createfolder', ['group' => $group, 'parent' => $parent])]) !!}

    <div class="form-group">
        <label for="name">{{ t('Name') }}</label>
        <input class="form-control" name="name" type="text" value="{{ old('name') }}" />

    </div>

    <div class="d-flex justify-content-between">
        <input class="btn btn-primary" type="submit" value="{{ t('Create') }}" />
        <a class="js-back" href="#" up-dismiss>{{ t('Cancel') }}</a>
    </div>

    {!! Form::close() !!}
@endsection
