@extends('app')

@section('head')
{!! HTML::style('/packages/datetimepicker/jquery.datetimepicker.css') !!}
@stop

@section('footer')
{!! HTML::script('/packages/datetimepicker/jquery.datetimepicker.full.min.js') !!}
<script>

/*
TODO locale :
*/
$.datetimepicker.setLocale('fr');

jQuery('input[name=start]').datetimepicker({
  format:'Y-d-m H:i',
  lang: 'fr',
  step: 30


});

jQuery('input[name=stop]').datetimepicker({
  format:'Y-d-m H:i',
  step: 30

}).setLocale('fr');


</script>
@stop



@section('content')

<input id="datetimepicker" type="text" >



@include('partials.grouptab')

<h1>Create an action</h1>


{!! Form::open(array('action' => ['ActionController@store', $group->id])) !!}

@include('actions.form')

<div class="form-group">
  {!! Form::submit('Create an action', ['class' => 'btn btn-primary form-control']) !!}
</div>


{!! Form::close() !!}

@include('partials.errors')



@endsection
