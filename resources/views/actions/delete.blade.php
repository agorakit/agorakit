@extends('dialog')

@section('content')
    <div>
        <h1>{{ trans('messages.delete_confirm_title') }}</h1>

        <p>{{ $action->name }}</p>

        {!! Form::model($action, ['method' => 'DELETE', 'action' => ['GroupActionController@destroy', $group, $action]]) !!}

        <div class="d-flex justify-content-between">
            <div class="form-group">
                {!! Form::submit(trans('messages.delete_confirm_button'), ['class' => 'btn btn-danger']) !!}
            </div>
            <div>
                <a class="btn btn-link js-back" href="#" up-dismiss>{{ __('messages.cancel') }}</a>
            </div>
        </div>

        {!! Form::close() !!}

    </div>
@endsection
