@extends('app')

@section('content')

@include('partials.grouptab')

<h1>Leaving {{$group->name}} group</h1>

<p>
  We are sad to see you go... confirm that you want to leave this group. You won't receive notifications anymore.
</p>


{!! Form::open(array('action' => ['MembershipController@destroy', $group->id])) !!}

<div class="form-group">
{!! Form::submit('Leave this group', ['class' => 'btn btn-primary form-control']) !!}
</div>
<a href="{{url('/')}}">I changed my mind, I don't want to leave this group</a>

{!! Form::close() !!}

@include('partials.errors')

@endsection
