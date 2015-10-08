@extends('app')

@section('content')


@include('partials.grouptab')

    <h2>{{ $group->name }}</h2>

<p>
            {{ $group->body }}
</p>

<h2>Latest discussions in this group</h2>

    <table class="table table-hover">
      @foreach( $discussions as $discussion )
      <tr>
        <td>
          <a href="{{ action('DiscussionController@show', [$group->id, $discussion->id]) }}">{{ $discussion->name }}</a>
        </td>

        <td>
          @unless (is_null ($discussion->user))
          <a href="{{ action('UserController@show', $discussion->user->id) }}">{{ $discussion->user->name }}</a>
          @endunless
        </td>

        <td>
          <a href="{{ action('DiscussionController@show', [$group->id, $discussion->id]) }}">{{ $discussion->created_at->diffForHumans() }}</a>
        </td>

        <td>
          <a href="{{ action('DiscussionController@show', [$group->id, $discussion->id]) }}">{{ $discussion->comments->count() }} replies</a>
        </td>

      </tr>
      @endforeach
    </table>



<a class="btn btn-primary" href="{{ action('DiscussionController@create', $group->id ) }}">New discussion</a>



<h2>Latest files in this group</h2>
@foreach ($files as $file)

<li>{{ $file->name }}</li>

@endforeach



@endsection
