@extends('app')

@section('content')

<div class="container">
    <h2>All the groups on this server</h2>
        <ul>
            @foreach( $groups as $group )

            <li>
            <a href="{{ action('GroupController@show', $group->id) }}">{{ $group->name }}</a>
            @if($group->replied)
            <span class="glyphicon glyphicon-ok" title="Vous avez répondu à cette question"></span>
            @endif
            </li>
            @endforeach
        </ul>
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
