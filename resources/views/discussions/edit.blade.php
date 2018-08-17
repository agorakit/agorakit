@extends('app')

@section('content')

@include('groups.tabs')
<div class="tab_content">
  <h1>{{trans('messages.modify')}}</h1>


  {!! Form::model($discussion, array('action' => ['GroupDiscussionController@update', $discussion->group->id, $discussion->id])) !!}

  @include('discussions.form')

  <div class="form-group">
    {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
