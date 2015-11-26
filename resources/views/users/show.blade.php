@extends('app')

@section('content')

<h1>{{ $user->name }}</h1>
<p>
Inscription :  {{ $user->created_at->diffForHumans() }}
</p>

<p>
  <a class="btn btn-primary" href="{{action('UserController@contact', $user->id)}}"><i class="fa fa-envelope-o"></i>
 {{trans('messages.contact_this_user')}}</a>
</p>



@endsection
