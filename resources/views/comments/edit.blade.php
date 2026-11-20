@extends('app')

@section('content')
    <h1>{{ t('Edit a comment') }}</h1>

    {!! Form::model($comment, ['action' => ['CommentController@update', $group, $discussion, $comment], 'files' => true]) !!}

    @include('comments.form')

    <div class="form-group mt-4">
        {!! Form::submit(t('Save'), ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection
