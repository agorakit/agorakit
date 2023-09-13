@extends('dialog')

@section('content')

<div class="tab_content">
    <h1>{{trans('messages.delete_confirm_title')}}</h1>

    <p>{{$action->name}}</p>

    {!! Form::model($action, array('method' => 'DELETE', 'action' => ['GroupActionController@destroy', $group,
    $action])) !!}



    <div class="flex justify-content-between">
        <div class="form-group">
            {!! Form::submit(trans('messages.delete_confirm_button'), ['class' => 'btn btn-danger']) !!}
        </div>
        <div>
            <a href="#" class="btn btn-link js-back">{{__('messages.cancel')}}</a>
        </div>
    </div>

    {!! Form::close() !!}


</div>

@endsection