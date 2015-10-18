@extends('app')

@section('content')


<h1>Joining {{$group->name}} group</h1>

<p>
  Here you can choose if and how much email notifications you will receive from this group.
  We will never send you more that what you ask.
</p>


{!! Form::open(array('action' => ['MembershipController@store', $group->id])) !!}

@include('membership.form')

<div class="form-group">
{!! Form::submit('Join this group', ['class' => 'btn btn-primary form-control']) !!}
<a href="{{url('/')}}">I changed my mind, cancel</a>
</div>


{!! Form::close() !!}

@include('partials.errors')

@endsection
