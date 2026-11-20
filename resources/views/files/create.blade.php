@extends('dialog')

@section('content')
    <h1>{{ t('Upload File') }}</h1>


    {!! Form::open([
        'url' => route('groups.files.create', ['group' => $group, 'parent' => $parent]),
        'files' => true,
    ]) !!}
    <div class="form-group mt-4 mb-4">
        <input id="file" name="file" title="{{ t('Select one file') }}"
            type="file">
        <div class="small-help">
            <i class="fas fa-info-circle"></i>
            {{ t('Maximum file size') }} {{ sizeForHumans(config('agorakit.max_file_size') * 1000) }}
        </div>
    </div>



    @include('partials.tags_input')



    <div class="flex justify-content-between align-items-center my-8">
        <input class="btn btn-primary" type="submit" value="{{ t('Create') }}" />
        <a class="js-back" href="#" up-dismiss>{{ t('Cancel') }}</a>
    </div>

    {!! Form::close() !!}
@endsection
