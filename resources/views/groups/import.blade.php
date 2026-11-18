@extends('dialog')

@section('content')
    <h1>{{ trans('Import a Group') }}</h1>

    {!! Form::open([
        'url' => route('groups.import'),
        'files' => true,
    ]) !!}
    <div class="form-group mt-4 mb-4">
        <input id="import" name="import" title="{{ trans('Select one file') }}"
            type="file" accept=".zip, .json">
        <div class="small-help">
            <i class="fas fa-info-circle"></i>
            {{ trans('Maximum file size') }} {{ sizeForHumans(config('agorakit.max_file_size') * 1000) }}
        </div>
    </div>

    <div class="flex justify-content-between align-items-center my-8">
        <input class="btn btn-primary" type="submit" value="{{ trans('Import') }}" />
        <a class="js-back" href="#" up-dismiss>{{ trans('Cancel') }}</a>
    </div>

    {!! Form::close() !!}
@endsection

