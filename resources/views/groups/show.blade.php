@extends('app')

@section('content')

@include('partials.grouptab')

<div class="tab_content">




  <h2>{{trans('group.about_this_group')}}  </h2>

  <img class="cover" src="{{action('GroupController@cover', $group->id)}}"/>
  <p>
    {!! filter($group->body) !!}
  </p>


  <p>
    @can('update', $group)
    <a class="btn btn-default btn-xs" href="{{ action('GroupController@edit', [$group->id]) }}">
      <i class="fa fa-pencil"></i>
      {{trans('messages.edit')}}
    </a>
    @endcan


    @if ($group->revisionHistory->count() > 0)
    <a class="btn btn-default btn-xs" href="{{action('GroupController@history', $group->id)}}">
      <i class="fa fa-history"></i>
      {{trans('messages.show_history')}}
    </a>
    @endif

  </p>





  <h2>{{trans('group.latest_actions')}}</h2>

  @if($actions->count() > 0)
  <table class="table table-hover special">
    <thead>
      <tr>
        <th>{{trans('messages.date')}}</th>
        <th style="width: 75%">{{trans('messages.title')}}</th>

        <th>{{trans('messages.where')}}</th>
      </tr>
    </thead>

    <tbody>
      @foreach ($actions as $action)
      <tr>

        <td>
          {{$action->start->format('d/m/Y H:i')}}
        </td>

        <td>
          <a href="{{ action('ActionController@show', [$group->id, $action->id]) }}">
            <span class="name">{{ $action->name }}</span>
            <span class="summary">{{ summary($action->body) }}</span></a>
          </td>

          <td>
            {{$action->location}}
          </td>

        </tr>

        @endforeach
      </table>
      @else
      {{trans('messages.nothing_yet')}}
      @endif



      <h2>{{trans('group.latest_discussions')}}</h2>

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







      <h2>{{trans('group.latest_files')}}</h2>

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
            <a href="{{ action('FileController@show', [$group->id, $file->id]) }}">{{trans('file.download')}}</a>
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

    </div>



    @endsection
