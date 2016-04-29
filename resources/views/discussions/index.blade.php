@extends('app')

@section('content')

@include('partials.grouptab')
<div class="tab_content">

  @include('partials.invite')
  
  <h2>{{trans('discussion.all_in_this_group')}}

    @can('create-discussion', $group)
    <a class="btn btn-primary btn-xs" href="{{ action('DiscussionController@create', $group->id ) }}">
      <i class="fa fa-plus"></i> {{trans('discussion.create_one_button')}}
    </a>
    @endcan
    </h2>

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
            <a href="{{ action('DiscussionController@show', [$discussion->group_id, $discussion->id]) }}">
              <span class="name">{{ $discussion->name }}</span>
              <span class="summary">{{summary($discussion->body) }}</span>
            </a>
          </td>

          <td>
            {{ $discussion->updated_at->diffForHumans() }}
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
      </tbody>
    </table>


    {!! $discussions->render() !!}


  </div>




  @endsection
