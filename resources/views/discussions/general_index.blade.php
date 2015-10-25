@extends('app')

@section('content')

<div class="container">

  <div class="page-header">
  <h1>{{ trans('messages.unread_discussions') }}</a></h1>
  </div>


    <table class="table table-hover">
      @forelse( $discussions as $discussion )
      <tr>
        <td>
          <a href="{{ action('DiscussionController@show', [$discussion->group->id, $discussion->id]) }}">{{ $discussion->name }}</a>
        </td>

        <td>
          @unless (is_null ($discussion->user))
          <a href="{{ action('UserController@show', $discussion->user->id) }}">{{ $discussion->user->name }}</a>
          @endunless
        </td>

        <td>
          <a href="{{ action('DiscussionController@show', [$discussion->group->id, $discussion->id]) }}">Posted in {{ $discussion->group->name }}</a>
        </td>

        <td>
          {{ $discussion->updated_at->diffForHumans() }}
        </td>



        <td>

          Total : {{$discussion->total_comments}}
          /
          Unread : {{$discussion->unread_count}}



          @if ($discussion->unReadCount() > 0)

          <i class="fa fa-comment"></i>
          <span class="badge">{{ $discussion->unReadCount() }}</span>



          @endif
        </td>

      </tr>
      @empty
        {{trans('messages.nothing_yet')}}
      @endforelse
    </table>




  </div>




  @endsection
