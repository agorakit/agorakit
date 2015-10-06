@extends('app')

@section('content')

<div class="container">
    <h2>All the discussions in this group</h2>
        <ul>
            @foreach( $discussions as $discussion )

            <li>
            <a href="{{ action('DiscussionController@show', [$group->id, $discussion->id]) }}">{{ $discussion->name }}</a>
            </li>
            @endforeach
        </ul>
</div>




@endsection
