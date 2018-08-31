@extends('dialog')

@section('content')

        <h1>{{trans('messages.delete_confirm_title')}}</h1>


        <em>
                <strong>{{$discussion->name}}</strong>
                <p>{{summary($discussion->body)}}</p>
        </em>


        {!! Form::model($discussion, array('method' => 'DELETE', 'action' => ['GroupDiscussionController@destroy', $group->id, $discussion->id])) !!}



        <div class="mt-5 d-flex justify-content-between align-items-center">
                {!! Form::submit(trans('messages.delete_confirm_button'), ['class' => 'btn btn-danger']) !!}
        </div>


        {!! Form::close() !!}




@endsection
