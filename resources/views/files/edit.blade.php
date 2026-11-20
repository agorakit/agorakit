@extends('app')

@section('content')

    {!! Form::model($file, ['action' => ['GroupFileController@update', $file->group, $file], 'files' => true]) !!}

    <div class="form-group">
        {!! Form::label('name', t('Filename')) !!}
        {!! Form::text('name', $file->name, ['class' => 'form-control', 'required']) !!}
    </div>

    @if ($file->isFile())
        <div class="form-group mt-4 mb-4">
            {!! Form::label('file', t('Upload a new version of this file')) !!}
            <input class="form-control" id="file" name="file" type="file" title="{{ t('Upload a new version of this file') }}">
        </div>
    @endif

    @if ($folders->count() > 0)
        <div class="form-group">
            {!! Form::label('parent', t('Folder')) !!}
            <select class="form-control" name="parent">
                <option value="root" @if ($file->parent_id == null) selected="selected" @endif>{{ t('Root') }}</option>
                @foreach ($folders as $folder)
                    <option value="{{ $folder->id }}" @if ($file->parent_id == $folder->id) selected="selected" @endif>{{ $folder->name }}</option>
                @endforeach
            </select>
        </div>
    @endif

    @include('partials.tags_input')

    <div class="form-group">
        {!! Form::submit(t('Save'), ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}

@endsection
