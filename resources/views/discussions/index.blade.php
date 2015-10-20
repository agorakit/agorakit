@extends('app')

@section('content')

@include('partials.grouptab')

<div class="container">
  <h2>{{trans('discussion.all_in_this_group')}} <a class="btn btn-primary btn-xs" href="{{ action('DiscussionController@create', $group->id ) }}">
    <i class="fa fa-plus"></i>
{{trans('discussion.create_one_button')}}</a></h2>

    <table class="table table-hover">
      @forelse( $discussions as $discussion )
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
          <a href="{{ action('DiscussionController@show', [$group->id, $discussion->id]) }}">{{ $discussion->updated_at->diffForHumans() }}</a>
        </td>



        <td>
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

    {!! $discussions->render() !!}


  </div>




  @endsection
