@extends('app')

@section('content')

@include('groups.tabs')
<div class="tab_content">
  <h1>Tag</h1>


  {!! Form::model($item, array('action' => ['TagController@update', $item->group->id, $item->id])) !!}



  <div class="form-group">
    {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
