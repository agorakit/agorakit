@extends('app')

@section('content')

@include('groups.tabs')
<div class="tab_content">
  <h1>{{trans('messages.modify')}} <strong>"{{$action->name}}"</strong></h1>
  


  {!! Form::model($action, array('action' => ['GroupActionController@update', $action->group, $action])) !!}

  @include('actions.form')

  <div class="form-group">
    {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary btn-lg']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
