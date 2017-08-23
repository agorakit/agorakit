@extends('app')

@section('content')

@include('groups.tabs')
<div class="tab_content">
  <h1>{{trans('messages.edit_item')}}</h1>


  {!! Form::model($group, array('action' => ['GroupController@update', $group->id], 'files' => true)) !!}

  @include('groups.form')

  <div class="form-group">
    {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
