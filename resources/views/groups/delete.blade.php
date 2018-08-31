@extends('app')

@section('content')

@include('groups.tabs')
<div class="tab_content">
  <h1>{{trans('messages.delete_confirm_title')}}</h1>

  <p>{{$group->name}}</p>

  {!! Form::model($group, array('method' => 'DELETE', 'action' => ['GroupController@destroy', $group])) !!}



  <div class="form-group">
              {!! Form::submit(trans('messages.delete_confirm_button'), ['class' => 'btn btn-danger']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
