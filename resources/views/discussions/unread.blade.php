@extends('app')

@section('content')

<div class="container">

  <div class="page-header">
    <h1>{{ trans('messages.unread_discussions') }}</a></h1>
  </div>



  <table class="table table-hover special">
    <thead>
       <tr>
           <th style="width: 75%">Titre</th>
           <th>Date</th>
           <th>A lire</th>
       </tr>
   </thead>

   <tbody>
    @forelse( $discussions as $discussion )
    <tr>
      <td class="content">
        <a class="name" href="{{ action('DiscussionController@show', [$discussion->group_id, $discussion->id]) }}">{{ $discussion->name }}</a>
          <span class="summary">{{ str_limit($discussion->body, 200) }}</span>
      </td>




      <td>
        {{ $discussion->updated_at_human }}
      </td>



      <td>
        <i class="fa fa-comment"></i>
        <span class="badge">{{ $discussion->total_comments - $discussion->read_comments }}</span>
      </td>




    </tr>
    @empty
    {{trans('messages.nothing_yet')}}
  </tbody>
  </table>


  @endforelse
</div>




@endsection
