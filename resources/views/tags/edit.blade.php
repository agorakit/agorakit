@extends('dialog')

@section('content')


  <h1>Edit Tag of <em>{{$name}}</em></h1>


  {!! Form::model($model, array('action' => ['TagController@update', $group, $type, $id])) !!}

  @include('partials.tags_form')


  <div class="form-group">
    {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
  </div>


  {!! Form::close() !!}



@endsection
