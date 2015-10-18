@extends('app')

@section('content')

@include('partials.grouptab')

<h1>Tour settings for the {{$group->name}} group</h1>

<p>
  Here you can choose if and how much email notifications you will receive from this group.
  We will never send you more that what you ask.
</p>


{!! Form::model($membership, array('action' => ['MembershipController@store', $group->id])) !!}

@include('membership.form')

<div class="form-group">
{!! Form::submit('Save my settings', ['class' => 'btn btn-primary form-control']) !!}
</div>


{!! Form::close() !!}

@include('partials.errors')

@endsection
