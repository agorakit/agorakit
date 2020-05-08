@extends('app')

@section('content')

  @include('groups.tabs')


  <div class="content">

    <div class="d-flex justify-content-between">

      <h1>
        {{ $action->name }}
      </h1>

      <div class="ml-4 dropdown">
        <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="far fa-edit" aria-hidden="true"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

          @can('update', $action)
            <a class="dropdown-item" href="{{ route('groups.actions.edit', [$group, $action]) }}">
              <i class="fa fa-pencil"></i>
              {{trans('messages.edit')}}
            </a>
          @endcan

          @can('delete', $action)
            <a class="dropdown-item" href="{{ route('groups.actions.deleteconfirm', [$group, $action]) }}">
              <i class="fa fa-trash"></i>
              {{trans('messages.delete')}}
            </a>
          @endcan

          @if ($action->revisionHistory->count() > 0)
            <a class="dropdown-item" href="{{route('groups.actions.history', [$group, $action])}}"><i class="fa fa-history"></i> {{trans('messages.show_history')}}</a>
          @endif
        </div>
      </div>
    </div>


    <div class="meta mb-3">
      {{trans('messages.started_by')}}
      <span class="user">
        <a up-follow href="{{ route('users.show', [$action->user]) }}">{{ $action->user->name}}</a>
      </span>
      {{trans('messages.in')}}
      <strong>
        <a up-follow href="{{ route('groups.show', [$action->group]) }}">{{ $action->group->name}}</a>
      </strong>
      {{ $action->created_at->diffForHumans()}}
    </div>


    <div class="tags mb-3">
      @if ($action->tags->count() > 0)
        @foreach ($action->tags as $tag)
          @include('tags.tag')
        @endforeach
      @endif
    </div>


    <h3>{{trans('messages.begins')}} : {{$action->start->format('d/m/Y H:i')}}</h3>

    <h3>{{trans('messages.ends')}} : {{$action->stop->format('d/m/Y H:i')}}</h3>

    @if (!empty($action->location))
      <h3>{{trans('messages.location')}} : {{$action->location}}</h3>
    @endif



    <div>
      {!! filter($action->body) !!}
    </div>

    @if ($action->users->count() > 0)
      <div class="d-flex justify-content-between mt-5 mb-4">
        <h2>{{trans('messages.user_attending')}} ({{$action->users->count()}})</h2>
        <div>
          @if (Auth::user() && Auth::user()->isAttending($action))
            <a class="btn btn-primary btn-sm" up-modal=".dialog" href="{{route('groups.actions.unattend', [$group, $action])}}">{{trans('messages.unattend')}}</a>
          @endif
        </div>
      </div>
      <div class="d-flex flex-wrap users mt-2 mb-2">
        @foreach($action->users as $user)
          @include('users.user-card')
        @endforeach
      </div>

    @endif


    <div class="mt-4">
      @if (Auth::user() && !Auth::user()->isAttending($action))
        <a class="btn btn-primary" up-modal=".dialog" href="{{route('groups.actions.attend', [$group, $action])}}">{{trans('messages.attend')}}</a>
      @endif
    </div>
  </div>





</div>

@endsection
