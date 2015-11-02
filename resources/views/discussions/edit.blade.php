@extends('app')

@section('content')

@include('partials.grouptab')
<div class="tab_content">
  <h1>Modify a discussion</h1>


  {!! Form::model($discussion, array('action' => ['DiscussionController@update', $discussion->group->id, $discussion->id])) !!}

  @include('discussions.form')

  <div class="form-group">
    {!! Form::submit('Modify a discussion', ['class' => 'btn btn-primary form-control']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
