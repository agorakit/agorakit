@extends('app')

@section('head')
{!! HTML::style('/packages/datetimepicker/jquery.datetimepicker.css') !!}
@stop

@section('footer')
{!! HTML::script('/packages/datetimepicker/jquery.datetimepicker.full.min.js') !!}

<script>
$.datetimepicker.setLocale('{{App::getLocale()}}');

/*
jQuery(function(){
  jQuery('#start').datetimepicker({
    format:'Y-m-d H:i',
    step: 30,
    dayOfWeekStart: 1,
    onShow:function( ct ){
      this.setOptions({
        maxDate:jQuery('#stop').val()?jQuery('#stop').val():false
      })
    }

  });
  jQuery('#stop').datetimepicker({
    format:'Y-m-d H:i',
    step: 30,
    dayOfWeekStart: 1,
    onShow:function( ct ){
      this.setOptions({
        minDate:jQuery('#start').val()?jQuery('#start').val():false
      })
    }

  });
});
*/

jQuery(function(){
  jQuery('#start').datetimepicker({
    format:'Y-m-d H:i',
    step: 30,
    dayOfWeekStart: 1
  });

  jQuery('#stop').datetimepicker({
    format:'Y-m-d H:i',
    step: 30,
    dayOfWeekStart: 1,
    onShow:function( ct ){
      this.setOptions({
        minDate:jQuery('#start').val()
      })
    }

  });
});


</script>



@stop


@section('content')



@include('partials.grouptab')

<h1>Create an action</h1>


{!! Form::open(array('action' => ['ActionController@store', $group->id])) !!}

@include('actions.form')

<div class="form-group">
  {!! Form::submit('Create an action', ['class' => 'btn btn-primary form-control']) !!}
</div>



{!! Form::close() !!}





@endsection
