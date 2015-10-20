@extends('app')

@section('content')



<h1>{{trans('group.create_group_title')}}</h1>


{!! Form::open(array('action' => ['GroupController@store'])) !!}

@include('groups.form')

<div class="form-group">
{!! Form::submit(trans('group.create_button'), ['class' => 'btn btn-primary form-control']) !!}
</div>


{!! Form::close() !!}

@include('partials.errors')



@endsection
