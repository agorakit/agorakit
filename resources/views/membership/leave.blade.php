@extends('app')

@section('content')

@include('groups.tabs')

<div class="tab_content">

<h1>{{ trans('messages.leaving_the_group_called') }} "{{$group->name}}"</h1>

<div class="help">
<p>
{{ trans('messages.leaving_help_message') }}
</p>
</div>


{!! Form::open(array('action' => ['MembershipController@destroy', $group->id])) !!}

<div class="form-group">
{!! Form::submit(trans('messages.leave_this_group'), ['class' => 'btn btn-primary form-control']) !!}
</div>
<a href="{{url('/')}}">{{ trans('messages.i_changed_my_mind') }}</a>

{!! Form::close() !!}

@include('partials.errors')

</div>

@endsection
