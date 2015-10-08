@extends('app')

@section('content')

<div class="container">
    <h2>All the groups on this server</h2>

        <table class="table table-hover">
            @foreach( $groups as $group )

            <tr>

              <td><a href="{{ action('GroupController@show', $group->id) }}">{{ $group->name }}</a></td>
              <td><a href="{{ action('GroupController@show', $group->id) }}">{{ $group->body }}</a></td>

            </tr>
            @endforeach
        </table>
</div>

<div class="container">
@if ($mygroups)
    <h2>Your groups</h2>
        <ul>
            @foreach( $mygroups as $group )
            <li>
            <a href="{{ action('GroupController@show', $group->id) }}">{{ $group->name }}</a>
            </li>
            @endforeach
        </ul>

@else
<h2>You are not a member or of any group or are not connected yet</h2>
@endif

</div>



@endsection
