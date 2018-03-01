@extends('app')

@section('content')

    @include('groups.tabs')


    <div class="content">

        <div class="meta">{{trans('messages.started_by')}} <span class="user"><a href="{{ route('users.show', [$action->user->id]) }}">{{ $action->user->name}}</a></span>, {{trans('messages.in')}} <a href="{{ route('groups.actions.index', [$group->id]) }}">{{ $action->group->name}}</a> {{ $action->created_at->diffForHumans()}} </div>

        <div class="d-flex justify-content-between">
            <h3>
                {{ $action->name }}
            </h3>

            <div class="ml-4 dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-wrench" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                    @can('update', $action)
                        <a class="dropdown-item" href="{{ route('groups.actions.edit', [$group->id, $action->id]) }}">
                            <i class="fa fa-pencil"></i>
                            {{trans('messages.edit')}}
                        </a>
                    @endcan

                    @can('delete', $action)
                        <a class="dropdown-item" href="{{ route('groups.actions.deleteconfirm', [$group->id, $action->id]) }}">
                            <i class="fa fa-trash"></i>
                            {{trans('messages.delete')}}
                        </a>
                    @endcan

                    @if ($action->revisionHistory->count() > 0)
                        <a class="dropdown-item" href="{{route('groups.actions.history', [$group->id, $action->id])}}"><i class="fa fa-history"></i> {{trans('messages.show_history')}}</a>
                    @endif
                </div>
            </div>

        </div>

        <p class="body">
            {{$action->start->format('d/m/Y H:i')}} - {{$action->stop->format('d/m/Y H:i')}}
            {!! filter($action->body) !!}
        </p>

        <h4>{{trans('messages.when')}} ?</h4>
        <p>{{trans('messages.begins')}} : {{$action->start->format('d/m/Y H:i')}}</p>
        <p>{{trans('messages.ends')}} : {{$action->stop->format('d/m/Y H:i')}}</p>

        <h4>{{trans('messages.where')}} ?</h4>
        <p>{{$action->location}}</p>


        <h4>{{trans('messages.user_attending')}}
            @if (Auth::user() && Auth::user()->isAttending($action))
                <a class="btn btn-outline-primary" up-modal=".main" href="{{route('groups.actions.unattend', [$group, $action])}}">{{trans('messages.unattend')}}</a>
            @elseif (Auth::user() && !Auth::user()->isAttending($action))
                <a class="btn btn-outline-primary" up-modal=".main" href="{{route('groups.actions.attend', [$group, $action])}}">{{trans('messages.attend')}}</a>
            @endif
        </h4>

        <div class="users">
            @forelse($action->users as $user)
                @include('users.user-simple')
            @empty
                {{trans('messages.nothing_yet')}}
            @endforelse
        </div>



    </div>

</div>

@endsection
