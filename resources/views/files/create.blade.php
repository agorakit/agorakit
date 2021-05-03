@extends('dialog')

@section('content')


<h1>{{ trans('messages.upload_files') }}</h1>


{!! Form::open(['url' => route('groups.files.create', ['group' => $group, 'parent' => $parent]), 'files' => true]) !!}
<div class="form-group mt-4 mb-4">
    <input name="files[]" id="file" type="file" multiple="mutiple"
        title="{{ trans('messages.select_one_or_more_files') }}">
        <div class="small-help">
            <i class="fas fa-info-circle"></i>
            {{trans('messages.max_file_size')}} {{config('agorakit.max_file_size')}} Kb
        </div>
</div>



@include('partials.tags_input')



<div class="flex justify-between items-center my-8">
    <input class="btn btn-primary" type="submit" value="{{ trans('messages.create') }}" />
    <a href="#" class="js-back">{{ trans('messages.cancel') }}</a>
</div>

{!! Form::close() !!}


@endsection