@extends('app')

@section('content')

@include('partials.grouptab')
<div class="tab_content">
  <h1>Modification du groupe</h1>


  {!! Form::model($group, array('action' => ['GroupController@update', $group->id])) !!}

  @include('groups.form')

  <div class="form-group">
    {!! Form::submit('Modifer le groupe', ['class' => 'btn btn-primary form-control']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
