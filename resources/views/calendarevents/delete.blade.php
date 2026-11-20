@extends('dialog')

@section('content')
    <div>
        <h1>{{ t('Are you sure you want to delete?') }}</h1>

        <p>{{ $event->name }}</p>

        {!! Form::model($event, ['method' => 'DELETE', 'action' => ['GroupCalendarEventController@destroy', $group, $event]]) !!}

        <div class="d-flex justify-content-between">
            <div class="form-group">
                {!! Form::submit(t('Delete'), ['class' => 'btn btn-danger']) !!}
            </div>
            <div>
                <a class="btn btn-link js-back" href="#" up-dismiss>{{ t('Cancel') }}</a>
            </div>
        </div>

        {!! Form::close() !!}

    </div>
@endsection
