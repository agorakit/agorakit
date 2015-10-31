@extends('app')

@section('content')


<h1>{{trans('membership.invite_title')}} {{$group->name}} </h1>

<p>
  {{trans('membership.invite_intro')}}

</p>


{!! Form::open(array('action' => ['InviteController@sendInvites', $group->id])) !!}

<div class="form-group">
    {!! Form::label('invitations', trans('membership.people_to_invite')) !!}
    {!! Form::textarea('invitations', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
{!! Form::submit(trans('membership.invite_button'), ['class' => 'btn btn-primary form-control']) !!}
<a href="{{url('/')}}">{{trans('messages.cancel')}}</a>
</div>


{!! Form::close() !!}

@include('partials.errors')

@endsection
