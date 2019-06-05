@extends('app')

@section('content')

@include('groups.tabs')
<div class="tab_content">
  <h1 class="mb-3">{{trans('Configuration')}}</h1>


  {!! Form::model($group, array('action' => ['GroupController@update', $group], 'files' => true)) !!}

  @include('groups.form')

  <div class="form-group">
    {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary btn-lg']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
