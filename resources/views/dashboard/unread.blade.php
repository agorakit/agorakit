@extends('app')

@section('content')



<div class="page-header">
  <h1>{{ trans('messages.unread_discussions') }}</a></h1>
</div>

@if ($discussions)

<table class="table table-hover special">
  <thead>
    <tr>
      <th style="width: 75%">Titre</th>
      <th>Date</th>
      <th>A lire</th>
    </tr>
  </thead>

  <tbody>
    @foreach( $discussions as $discussion )
    <tr>
      <td class="content">
        <a href="{{ action('DiscussionController@show', [$discussion->group_id, $discussion->id]) }}">
          <span class="name">{{ $discussion->name }}</span>
          <span class="summary">{{ str_limit($discussion->body, 200) }}</span>
        </a>
      </td>

      <td>
        {{ $discussion->updated_at_human }}
      </td>

      <td>
        <i class="fa fa-comment"></i>
        <span class="badge">{{ $discussion->total_comments - $discussion->read_comments }}</span>
      </td>

    </tr>
    @endforeach

  </tbody>
</table>
@else
{{trans('messages.nothing_yet')}}
@endif









@endsection
