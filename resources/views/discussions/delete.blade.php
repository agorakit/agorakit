@extends('dialog')

@section('content')
    <h1>{{ t('Are you sure you want to delete?') }}</h1>

    <em>
        <strong>{{ $discussion->name }}</strong>
        <p>{{ summary($discussion->body) }}</p>
    </em>

    {!! Form::model($discussion, [
        'method' => 'DELETE',
        'action' => ['GroupDiscussionController@destroy', $group, $discussion],
    ]) !!}

    <div class="d-flex justify-content-between">
        <div class="form-group">
            {!! Form::submit(t('Delete'), ['class' => 'btn btn-danger']) !!}
        </div>
        <div>
            <a class="btn btn-link js-back" href="#" up-dismiss>{{ t('Cancel') }}</a>
        </div>
    </div>

    {!! Form::close() !!}
@endsection
