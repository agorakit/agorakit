@extends('app')

@section('content')



<div class="page_header">
  <h1>{{ trans('messages.unread_discussions') }}</a></h1>
</div>


<div class="tab_content">
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
          <span class="summary">{{ $discussion->summary() }}</span>
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
</div>









@endsection
