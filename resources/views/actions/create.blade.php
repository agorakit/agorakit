@extends('app')




@section('content')

@include('partials.grouptab')
<div class="tab_content">



  <h1>Créer une action</h1>


  {!! Form::open(array('action' => ['ActionController@store', $group->id])) !!}

  @include('actions.form')

  <div class="form-group">
    {!! Form::submit('Créer une action', ['class' => 'btn btn-primary form-control']) !!}
  </div>



  {!! Form::close() !!}



</div>


@endsection
