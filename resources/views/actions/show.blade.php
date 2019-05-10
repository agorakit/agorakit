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
          <i class="fa fa-cog" aria-hidden="true"></i>
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

    <div class="d-flex my-3">
      <div class="mr-2 avatar">
        <img src="{{route('users.cover', [$action->user, 'small'])}}" class="rounded-circle"/>
      </div>
      <div class="meta">
        <div>
          {{trans('messages.author')}} <a href="{{ route('users.show', [$action->user]) }}">{{ $action->user->name}}</a> {{ $action->created_at->diffForHumans()}}
        </div>
        <div>
          {{trans('messages.in')}} <a href="{{ route('groups.actions.index', [$group]) }}">{{ $action->group->name}}</a>
        </div>
      </div>
    </div>


    <div class="row my-5">

      <div class="col order-md-last mb-5">
        <div class="d-flex">
          <div class="mr-3">
            <i class="far fa-clock"></i>
          </div>
          <div>
            {{trans('messages.begins')}} : {{$action->start->format('d/m/Y H:i')}}
            <br/>
            {{trans('messages.ends')}} : {{$action->stop->format('d/m/Y H:i')}}
          </div>
        </div>

        @if (!empty($action->location))
          <div class="d-flex">
            <div class="mr-3">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div>
              {{$action->location}}
            </div>
          </div>
        @endif
      </div>


      <div class="col-md-8 ">
        {!! filter($action->body) !!}
        @if ($action->users->count() > 0)
          <div class="d-flex justify-content-between mt-5 mb-4">
            <h2>{{trans('messages.user_attending')}} ({{$action->users->count()}})</h2>
            <div>
              @if (Auth::user() && Auth::user()->isAttending($action))
                <a class="btn btn-primary" up-modal=".main" href="{{route('groups.actions.unattend', [$group, $action])}}">{{trans('messages.unattend')}}</a>
              @endif
            </div>
          </div>
          <div class="d-flex flex-wrap users mt-2 mb-2">
            @foreach($action->users as $user)
              @include('users.user-card')
            @endforeach
          </div>

        @endif


        @if (Auth::user() && !Auth::user()->isAttending($action))
          <a class="btn btn-primary" up-modal=".main" href="{{route('groups.actions.attend', [$group, $action])}}">{{trans('messages.attend')}}</a>
        @endif
      </div>





    </div>

  @endsection
