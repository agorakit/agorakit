@extends('dialog')

@section('content')
    <h1>{{ t('Create a link') }}</h1>

    <p>{{ t('Here you can add a link (URL). For example link to videos, shared documents, etc...
  Enter a correct URL starting with http:// and set a title for this link. You can also add tags.') }}</p>

    {!! Form::open(['url' => route('groups.files.createlink', ['group' => $group, 'parent' => $parent])]) !!}

    <div class="form-group">

        <label for="link">{{ t('Link') }}</label>
        <input class="form-control" name="link" placeholder="https://..." type="text" value="{{ old('link') }}" />

        <label for="title">{{ t('Title') }}</label>
        <input class="form-control" name="title" type="text" value="{{ old('title') }}" />

        @include('partials.tags_input')

    </div>

    <div class="d-flex justify-content-between">
        <input class="btn btn-primary" name="{{ t('Create') }}" type="submit" />
        <a class="js-back" href="#" up-dismiss>{{ t('Cancel') }}</a>
    </div>

    {!! Form::close() !!}
@endsection
