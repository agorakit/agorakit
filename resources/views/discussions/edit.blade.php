@extends('app')

@section('content')
  <h1>{{trans('Modify')}} <strong>"{{$discussion->name}}"</strong></h1>


  {!! Form::model($discussion, array('action' => ['GroupDiscussionController@update', $discussion->group, $discussion], 'files' => true)) !!}

  @include('discussions.form')

  <div class="form-group">
    {!! Form::submit(trans('Save'), ['class' => 'btn btn-primary']) !!}
  </div>


  {!! Form::close() !!}

@endsection
