@extends('app')

@section('content')

<h1>{{ $user->name }}</h1>
<p>
Inscription :  {{ $user->created_at->diffForHumans() }}
</p>

<p>
  <img src="{{$user->cover()}}" />
  {!! $user->body !!}
</p>


<p>
  <a class="btn btn-primary" href="{{action('UserController@contact', $user->id)}}"><i class="fa fa-envelope-o"></i>
 {{trans('messages.contact_this_user')}}</a>


 @can('update', $user)
 <a class="btn btn-primary" href="{{ action('UserController@edit', [$user->id]) }}">
   <i class="fa fa-pencil"></i>
   {{trans('messages.edit')}}
 </a>
 @endcan

</p>



@endsection
