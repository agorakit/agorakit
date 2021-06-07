@extends('dialog')

@section('content')

        <h1>{{trans('messages.delete_confirm_title')}}</h1>


        <em>
                <strong>{{$discussion->name}}</strong>
                <p>{{summary($discussion->body)}}</p>
        </em>


        {!! Form::model($discussion, array('method' => 'DELETE', 'action' => ['GroupDiscussionController@destroy', $group, $discussion])) !!}
        
        <div class="flex justify-between">
        <div class="form-group">
            {!! Form::submit(trans('messages.delete_confirm_button'), ['class' => 'btn btn-danger']) !!}
        </div>
        <div>
            <a href="#" class="btn btn-link js-back">{{__('messages.cancel')}}</a>
        </div>
    </div>



        {!! Form::close() !!}




@endsection
