@extends('app')

@section('content')

@include('groups.tabs')
<div class="tab_content">
  <h1>{{trans('messages.delete_confirm_title')}}</h1>

  <p>{{$file->name}}</p>

  {!! Form::model($file, array('method' => 'DELETE', 'action' => ['GroupFileController@destroy', $group, $file])) !!}



  <div class="form-group">
              {!! Form::submit(trans('messages.delete_confirm_button'), ['class' => 'btn btn-danger']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
