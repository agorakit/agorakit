@extends('app')

@section('content')

@include('partials.grouptab')
<div class="tab_content">
  <h1>Location</h1>


  {!! Form::model($item, array('action' => ['LocationController@update', $item->group->id, $item->id])) !!}



  <div class="form-group">
    {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
