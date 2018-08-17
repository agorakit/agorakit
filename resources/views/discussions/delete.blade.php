@extends('app')

@section('content')

    @include('groups.tabs')
    <div class="tab_content">
        <h1>{{trans('messages.delete_confirm_title')}}</h1>

        <p>{{$discussion->name}}</p>

        {!! Form::model($discussion, array('method' => 'DELETE', 'action' => ['GroupDiscussionController@destroy', $group->id, $discussion->id])) !!}



        <div class="form-group">
            {!! Form::submit(trans('messages.delete_confirm_button'), ['class' => 'btn btn-danger']) !!}
        </div>


        {!! Form::close() !!}


    </div>

@endsection
