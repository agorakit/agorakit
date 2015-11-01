@extends('app')

@section('content')


<h1>{{ trans('messages.leaving_the_group_called') }} {{$group->name}}</h1>

<p>
{{ trans('messages.leaving_help_message') }}
</p>


{!! Form::open(array('action' => ['MembershipController@leave', $group->id])) !!}

<div class="form-group">
{!! Form::submit(trans('messages.leave_this_group'), ['class' => 'btn btn-primary form-control']) !!}
</div>
<a href="{{url('/')}}">{{ trans('messages.i_changed_my_mind') }}</a>

{!! Form::close() !!}

@include('partials.errors')

@endsection
