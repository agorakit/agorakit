@extends('dialog')

@section('content')
    <h1>{{ trans('Create a link') }}</h1>

    <p>{{ trans('Here you can add a link (URL). For example link to videos, shared documents, etc...
  Enter a correct URL starting with http:// and set a title for this link. You can also add tags.') }}</p>

    {!! Form::open(['url' => route('groups.files.createlink', ['group' => $group, 'parent' => $parent])]) !!}

    <div class="form-group">

        <label for="link">{{ trans('Link') }}</label>
        <input class="form-control" name="link" placeholder="https://..." type="text" value="{{ old('link') }}" />

        <label for="title">{{ trans('Title') }}</label>
        <input class="form-control" name="title" type="text" value="{{ old('title') }}" />

        @include('partials.tags_input')

    </div>

    <div class="d-flex justify-content-between">
        <input class="btn btn-primary" name="{{ trans('Create') }}" type="submit" />
        <a class="js-back" href="#" up-dismiss>{{ trans('Cancel') }}</a>
    </div>

    {!! Form::close() !!}
@endsection
