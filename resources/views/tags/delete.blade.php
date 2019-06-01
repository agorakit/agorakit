@extends('dialog')

@section('content')

        <h1>{{trans('messages.delete_confirm_title')}} @include('tags.tag')</h1>

        {!! Form::model($tag, array('method' => 'DELETE', 'action' => ['GroupTagController@destroy', $group, $tag])) !!}



        <div class="mt-5 d-flex justify-content-between align-items-center">
                {!! Form::submit(trans('messages.delete_confirm_button'), ['class' => 'btn btn-danger']) !!}
        </div>


        {!! Form::close() !!}

@endsection
