@extends('app')

@section('content')

@include('partials.grouptab')


<div class="container">
    <h2>All the discussions in this group</h2>

        <table class="table table-hover">
            @foreach( $discussions as $discussion )

            <tr>

              <td>
            <a href="{{ action('DiscussionController@show', [$group->id, $discussion->id]) }}">{{ $discussion->name }}</a>
          </td>

          <td>
            @if ( !$discussion->user()->get()->isEmpty() )


        <a href="{{ action('UserController@show', $discussion->user()->first()->id) }}">{{ $discussion->user()->first()->name }}</a>
        @endif

      </td>

          <td>
        <a href="{{ action('DiscussionController@show', [$group->id, $discussion->id]) }}">{{ $discussion->created_at->diffForHumans() }}</a>
      </td>

          </tr>
            @endforeach
        </table>

{!! $discussions->render() !!}


<a class="btn btn-primary" href="{{ action('DiscussionController@create', $group->id ) }}">New discussion</a>

</div>




@endsection
