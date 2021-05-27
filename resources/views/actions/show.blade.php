@extends('app')

@section('content')

@include('groups.tabs')


<div class="content">

    <div class="flex justify-between mt-8">

        <h1>
            {{ $action->name }}
        </h1>

        <div class="dropdown">
            <a class="cursor-pointer hover:bg-gray-300 flex flex-col justify-center items-center rounded-full w-12 h-12 "
                type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-h"></i>
            </a>
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
                <a class="dropdown-item" href="{{route('groups.actions.history', [$group, $action])}}"><i
                        class="fa fa-history"></i> {{trans('messages.show_history')}}</a>
                @endif
            </div>
        </div>
    </div>


    <div class="meta mb-3">
        {{trans('messages.started_by')}}
        <span class="user">
            @if ($action->user)
            <a up-follow href="{{ route('users.show', [$action->user]) }}">{{ $action->user->name}}</a>
            @endif
        </span>
        {{trans('messages.in')}}
        <strong>
            <a up-follow href="{{ route('groups.show', [$action->group]) }}">{{ $action->group->name}}</a>
        </strong>
        {{ dateForHumans($action->created_at) }}
    </div>


    <div class="tags mb-3">
        @if ($action->getSelectedTags()->count() > 0)
        @foreach ($action->getSelectedTags() as $tag)
        @include('tags.tag')
        @endforeach
        @endif
    </div>


    <h3>{{trans('messages.begins')}} : {{$action->start->isoFormat('LLLL')}}</h3>

    <h3>{{trans('messages.ends')}} : {{$action->stop->isoFormat('LLLL')}}</h3>

    @if (!empty($action->location))
    <h3>{{trans('messages.location')}} : {{$action->location}}</h3>
    @endif



    <div>
        {!! filter($action->body) !!}
    </div>


    <div id="participate-{{$action->id}}">
        <div class="my-5">
            @include('participation.dropdown')
        </div>

        @if ($action->attending->count() > 0)
        <h3>{{trans('messages.user_attending')}} ({{$action->attending->count()}})</h3>


        <div class="flex flex-wrap users mt-2 mb-2">
            @foreach($action->attending as $user)
            @include('users.user-card')
            @endforeach
        </div>
        @endif


        @if ($action->notAttending->count() > 0)

        <h3>{{trans('messages.user_not_attending')}} ({{$action->notAttending->count()}})</h3>

        <div class="flex flex-wrap users mt-2 mb-2">
            @foreach($action->notAttending as $user)
            @include('users.user-card')
            @endforeach
        </div>

        @endif

   </div>
</div>





</div>

@endsection