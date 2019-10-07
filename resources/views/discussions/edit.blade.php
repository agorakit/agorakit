@extends('app')

@section('content')

@include('groups.tabs')
<div class="tab_content">
  <h1>{{trans('messages.modify')}} <strong>"{{$discussion->name}}"</strong></h1>


  {!! Form::model($discussion, array('action' => ['GroupDiscussionController@update', $discussion->group, $discussion], 'files' => true)) !!}

  @include('discussions.form')

  <div class="form-group">
    {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary btn-lg']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
