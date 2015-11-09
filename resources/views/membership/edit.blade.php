@extends('app')

@section('content')

@include('partials.grouptab')

<div class="tab_content">

<h1>Vos préférences pour le groupe "{{$group->name}}"</h1>

<div class="help">
  <h4>Comment ça marche?</h4>
  <p>
    {{trans('membership.settings_intro')}}
  </p>

</div>

{!! Form::open(array('action' => ['MembershipController@settings', $group->id])) !!}

@include('membership.form')

<div class="form-group">
{!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
</div>


{!! Form::close() !!}

@include('partials.errors')


<div class="spacer">
</div>


<p>Si vous souhaitez quitter ce groupe, <a href="{{action('MembershipController@leaveForm', $group->id)}}">cliquez ici</a></p>


</div>

@endsection
