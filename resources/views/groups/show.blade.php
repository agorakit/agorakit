@extends('app')

@section('content')


@include('partials.grouptab')

<h2>About this group</h2>
<p>
  {{ $group->body }}
</p>

<h2>Latest <a href="{{ action('DiscussionController@index', [$group->id]) }}">discussions</a> in this group</h2>

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




<h2>Latest <a href="{{ action('FileController@index', [$group->id]) }}">files</a> in this group</h2>
<table class="table table-hover">
  @forelse ($files as $file)
  <tr>
    <td>
      <a href="{{ action('FileController@show', [$group->id, $file->id]) }}"><img src="{{ action('FileController@thumbnail', [$group->id, $file->id]) }}"/></a>
    </td>

    <td>
      <a href="{{ action('FileController@show', [$group->id, $file->id]) }}">{{ $file->name }}</a>
    </td>

    <td>
      <a href="{{ action('FileController@show', [$group->id, $file->id]) }}">Download</a>
    </td>

    <td>
      @unless (is_null ($file->user))
      <a href="{{ action('UserController@show', $file->user->id) }}">{{ $file->user->name }}</a>
      @endunless
    </td>

    <td>
      {{ $file->created_at->diffForHumans() }}
    </td>

  </tr>
  @empty
  {{trans('messages.nothing_yet')}}

  @endforelse
</table>


@endsection
