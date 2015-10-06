@extends('app')

@section('content')


@include('partials.group')

    <h2>{{ $group->name }}</h2>

<p>
            {{ $group->body }}
</p>

<h2>Latest discussions in this group</h2>
@foreach ($discussions as $discussion)

<li><a href="{{ action('DiscussionController@show', [$group->id, $discussion->id]) }}">{{ $discussion->name }} </a></li>

@endforeach

<a class="btn btn-primary" href="{{ action('DiscussionController@create', $group->id ) }}">New discussion</a>



<h2>Latest files in this group</h2>
@foreach ($files as $file)

<li>{{ $file->name }}</li>

@endforeach



@endsection
