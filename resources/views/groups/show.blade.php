@extends('app')

@section('content')

@include('partials.grouptab')

<div class="tab_content">




  <h2>{{trans('group.about_this_group')}}   <a href="{{ action('GroupController@edit', [$group->id]) }}" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i>
      {{trans('messages.edit')}}</a></h2>
  <p>
    {{ $group->body }}
  </p>



  @if ($group->revisionHistory->count() > 0)
  <p>
    <a href="{{action('GroupController@history', $group->id)}}">Afficher l'historique des modifications</a>
  </p>
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
