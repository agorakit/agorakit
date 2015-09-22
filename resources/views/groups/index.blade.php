@extends('app')

@section('content')
    <h2>Groups</h2>


        <ul>
            @foreach( $groups as $group )

            <li>
            <a href="{{ url('groups', $group->id) }}">{{ $group->name }}</a>
            @if($group->replied)
            <span class="glyphicon glyphicon-ok" title="Vous avez répondu à cette question"></span>
            @endif
            </li>
            @endforeach
        </ul>


@endsection
