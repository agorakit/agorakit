@extends('dialog')

@section('content')
    <h1>{{ trans('messages.create_link') }}</h1>

    <p>{{ trans('messages.create_link_help') }}</p>

    {!! Form::open(['url' => route('groups.files.createlink', ['group' => $group, 'parent' => $parent])]) !!}

    <div class="form-group">

        <label for="link">{{ trans('messages.link') }}</label>
        <input class="form-control" name="link" type="text" placeholder="https://..." value="{{ old('link') }}" />

        <label for="title">{{ trans('messages.title') }}</label>
        <input class="form-control" name="title" type="text" value="{{ old('title') }}" />

        @include('partials.tags_input')

    </div>

    <div class="d-flex justify-content-between">
        <input class="btn btn-primary" type="submit" name="{{ trans('messages.create') }}" />
        <a href="#" class="js-back">{{ trans('messages.cancel') }}</a>
    </div>

    {!! Form::close() !!}
@endsection
