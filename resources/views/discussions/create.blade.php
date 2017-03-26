@extends('app')

@section('content')

@include('groups.tabs')

<div class="tab_content">

  <h1>{{trans('messages.create')}}</h1>


  {!! Form::open(array('action' => ['DiscussionController@store', $group->id])) !!}

  @include('discussions.form')

  <div class="form-group">
    {!! Form::submit(trans('messages.create'), ['class' => 'btn btn-primary form-control']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
