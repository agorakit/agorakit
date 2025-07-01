@extends('dialog')

@section('content')
    <h1>{{ trans('group.import_group') }}</h1>

    {!! Form::open([
        'url' => route('groups.import'),
        'files' => true,
    ]) !!}
    <div class="form-group mt-4 mb-4">
        <input id="import" name="import" title="{{ trans('messages.select_one_file') }}"
            type="file" accept=".zip, .json">
        <div class="small-help">
            <i class="fas fa-info-circle"></i>
            {{ trans('messages.max_file_size') }} {{ sizeForHumans(config('agorakit.max_file_size') * 1000) }}
        </div>
    </div>

    <div class="flex justify-content-between align-items-center my-8">
        <input class="btn btn-primary" type="submit" value="{{ trans('messages.import') }}" />
        <a class="js-back" href="#" up-dismiss>{{ trans('messages.cancel') }}</a>
    </div>

    {!! Form::close() !!}
@endsection

