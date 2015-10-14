@extends('app')



@section('content')


<div class="container">
  <h1>Groups <a href="{{ action('GroupController@create') }}" class="btn btn-primary">Create</a></h1>
<p>Here you will find all the groups on this server</p>
    <div class="row">
    @foreach( $groups as $i => $group )
    @if ((($i+1) % 4) == 1) </div><div class="row"> @endif

      <div class="col-sm-4 col-md-3">
        <div class="thumbnail">
          <a href="{{ action('GroupController@show', $group->id) }}">
          <img src="http://lorempixel.com/242/150/cats/{{ $group->id }}"/>
        </a>
          <div class="caption">
            <h4><a href="{{ action('GroupController@show', $group->id) }}">{{ $group->name }}</a></h4>
            <p>{{ str_limit($group->body, 100) }}</p>
            <p>
              @if ($group->membership() == 2)
                <td><a class="btn btn-default" href="{{ action('GroupUserController@leave', $group->id) }}">Leave</a></td>
              @else
                <td><a class="btn btn-primary" href="{{ action('GroupUserController@join', $group->id) }}">Join</a></td>
              @endif
              </p>
          </div>
        </div>
      </div>
    @endforeach
  </div>

</div>






  @endsection
