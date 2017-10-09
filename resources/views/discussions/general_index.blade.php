@extends('app')

@section('content')

<div class="page-header">
  <h1>{{ trans('messages.unread_discussions') }}</a></h1>
</div>

@foreach ($groups as $group)
<h2>{{$group->name}}</h2>

<table class="table table-hover">
  @forelse( $group->discussions as $discussion )

  @if ($discussion->unReadCount() > 0)
  <tr>
    <td>
      <a href="{{ route('groups.discussions.show', [$group->id, $discussion->id]) }}">{{ $discussion->name }}</a>
    </td>

    <td>
      @unless (is_null ($discussion->user))
      <a href="{{ route('users.show', $discussion->user->id) }}">{{ $discussion->user->name }}</a>
      @endunless
    </td>



    <td>
      {{ $discussion->updated_at->diffForHumans() }}
    </td>



    <td>
      <i class="fa fa-comment"></i>
      <span class="badge">{{ $discussion->unReadCount() }}</span>
    </td>

    @endif


  </tr>
  @empty
  {{trans('messages.nothing_yet')}}
  @endforelse
</table>

@endforeach


@endsection
