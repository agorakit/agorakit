@extends('dialog')

@section('content')
    <h1>{{ trans('messages.create_link') }}</h1>

    <p>{{ trans('messages.create_link_help') }}</p>

    {!! Form::open(['url' => route('groups.files.createlink', ['group' => $group, 'parent' => $parent])]) !!}

    <div class="form-group">

        {!! Form::label('link', trans('messages.link')) !!}
        <input class="form-control" id="link" name="link" placeholder="https://..." type="text" value="{{ old('link') }}" />

        {!! Form::label('title', trans('messages.title')) !!}
        <input class="form-control" id="title" name="title" type="text" value="{{ old('title') }}" />

        @include('partials.tags_input')

    </div>

    <div class="d-flex justify-content-between">
        <input class="btn btn-primary" name="{{ trans('messages.create') }}" type="submit" />
        <a class="js-back" href="#" up-dismiss>{{ trans('messages.cancel') }}</a>
    </div>

    {!! Form::close() !!}
@endsection
