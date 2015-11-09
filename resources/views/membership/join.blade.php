@extends('app')

@section('content')

@include('partials.grouptab')

<div class="tab_content">

  <h1>Rejoindre le groupe "{{$group->name}}"</h1>

<div class="help">
  <h4>Comment ça marche?</h4>
  <p>
    {{trans('membership.join_intro')}}
  </p>

</div>


  {!! Form::open(array('action' => ['MembershipController@join', $group->id])) !!}

  @include('membership.form')

  <div class="form-group">
    {!! Form::submit(trans('membership.join_button'), ['class' => 'btn btn-primary form-control']) !!}
    <a href="{{url('/')}}">{{trans('messages.cancel')}}</a>
  </div>


  {!! Form::close() !!}

  @include('partials.errors')
</div>


@endsection
