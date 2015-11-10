@extends('app')

@section('content')

@include('partials.grouptab')
<div class="tab_content">
  <h1>Modifer une action</h1>


  {!! Form::model($action, array('action' => ['ActionController@update', $action->group->id, $action->id])) !!}

  @include('actions.form')

  <div class="form-group">
    {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
