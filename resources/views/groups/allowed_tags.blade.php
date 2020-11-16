@extends('app')

@section('content')

@include('groups.tabs')
<div class="tab_content">
  <h1 class="mb-3">{{trans('group.allowed_tags_title')}}</h1>

  <div class="alert alert-primary">
  {{trans('group.allowed_tags_help')}}
  </div>

  {!! Form::open(array('action' => ['GroupTagController@update', $group])) !!}



  @include('partials.tags_input')

  <div class="form-group">
    {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary btn-lg']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
