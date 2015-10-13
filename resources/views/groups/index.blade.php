@extends('app')



@section('content')

<div class="container">
  <h1>Groups</h1>

  <table class="table table-hover">
    @foreach( $groups as $group )

    <tr>

      <td><a href="{{ action('GroupController@show', $group->id) }}">{{ $group->name }}</a></td>
      <td><a href="{{ action('GroupController@show', $group->id) }}">{{ $group->body }}</a></td>


      @if ($group->membership() == 2)
        <td><a class="btn btn-default" href="{{ action('GroupUserController@leave', $group->id) }}">Leave</a></td>
      @else
        <td><a class="btn btn-default" href="{{ action('GroupUserController@join', $group->id) }}">Join</a></td>
      @endif

    </tr>
    @endforeach
  </table>
</div>




<div class="container">
  <h2>All the groups on this server, with a kitten powered presentation</h2>

  <div class="row">
    @foreach( $groups as $group )

    <div class="col-xs-8 col-sm-4 col-md-3">
      <div class="groupbox">
        <div class="imgthumb img-responsive">
          <img src="http://lorempixel.com/250/250/cats/{{ $group->id }}">
        </div>
        <div class="caption">
          <h5><a href="{{ action('GroupController@show', $group->id) }}">{{ $group->name }}</a></h5>
          <a href="#" class="btn btn-default btn-xs pull-right" role="button">
            <i class="glyphicon glyphicon-edit"></i></a>
            <a href="{{ action('GroupController@show', $group->id) }}" class="btn btn-info btn-xs" role="button">Visit</a>
            <a href="{{ action('GroupController@show', $group->id) }}" class="btn btn-default btn-xs" role="button">Visit too</a>
          </div>
        </div>
      </div>

      @endforeach
    </row>
  </div>



  @endsection
