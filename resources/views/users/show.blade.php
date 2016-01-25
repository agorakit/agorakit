@extends('app')

@section('content')


@include('partials.usertab')

<div class="tab_content">

<h1>{{ $user->name }}</h1>
<p>
Inscription :  {{ $user->created_at->diffForHumans() }}
</p>

<p>
  <img src="{{$user->cover()}}" />
  {!! filter($user->body) !!}
</p>


</div>



@endsection
