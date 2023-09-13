@props(['action', 'participants' => false])

<div class="flex items-start py-5 border-b-2 border-gray-300 hover:bg-gray-200" id="action-{{$action->id}}">
    <a up-follow href="{{ route('groups.actions.show', [$action->group, $action]) }}" class="no-underline">
        <div
            class="border-gray-400 text-secondary bg-gray-200 border-2 flex-shrink-0 d-flex flex-col align-items-center justify-center h-12 w-12 rounded-lg mx-1">
            <div class="text-xl -mb-2 text-gray-800">{{ $action->start->format('d') }}</div>
            <div class="text-sm">{{ $action->start->isoFormat('MMM') }}</div>
        </div>
    </a>


    <div up-expand class="flex-grow min-w-0">
        
        
        <div class="mx-2">

            <div class="text-gray-900 text-lg truncate">
                <a up-follow href="{{ route('groups.actions.show', [$action->group, $action]) }}" class="no-underline">
                    {{$action->name}}
                </a>
            </div>
            @if ($action->attending->count() > 0)
            <div class="text-secondary text-xs">
                <i class="fa fa-users"></i> {{ $action->attending->count() }}
                {{ trans('participants') }}
            </div>
            @endif

            <div class="text-secondary text-xs">
                <i class="fa fa-clock-o"></i> {{ $action->start->format('H:i') }} -
                {{ $action->stop->format('H:i') }}
            </div>

            @if ($action->location)
            <div class="text-secondary text-xs overflow-ellipsis overflow-hidden h-4">
                <i class="fa fa-map-marker"></i> {{ $action->location }}
            </div>
            @endif



        </div>

        @if ($participants)
        @if ($action->attending->count() > 0)
        <div class="flex -space-x-5 overflow-hidden">
            @foreach($action->attending as $user)
            @include('users.avatar')
            @endforeach
        </div>
        @endif
        @endif
    </div>




    @can ('participate', $action)
    <div class="participate-dropdown ml-2 me-2" id="participate-{{$action->id}}">
        <button class="dropdown-toggle bg-transparent text-gray-700" type="button" id="dropdownMenuButton"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            @if (Auth::user()->isAttending($action))
            <i class="fa fa-calendar-check-o"></i>
            @elseif (Auth::user()->isNotAttending($action))
            <i class="fa fa-calendar-times-o"></i>
            @else
            <i class="fa fa-question-circle-o"></i>
            @endif
        </button>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

            <a up-target="#action-{{$action->id}}" up-cache="false" up-history="false" class="dropdown-item"
                href="{{route('groups.actions.participation.set', ['group' => $action->group, 'action' => $action, 'status' => 'yes'])}}">
                {{__('I will participate')}}
            </a>

            <a up-target="#action-{{$action->id}}" up-cache="false" up-history="false" class="dropdown-item"
                href="{{route('groups.actions.participation.set', ['group' => $action->group, 'action' => $action, 'status' => 'no'])}}">
                {{__('I will not participate')}}
            </a>



            <a up-target="#action-{{$action->id}}" up-cache="false" up-history="false" class="dropdown-item"
                href="{{route('groups.actions.participation.set', ['group' => $action->group, 'action' => $action, 'status' => 'maybe'])}}">
                {{__('I don\'t know yet')}}
            </a>

        </div>
    </div>
    @endcan


</div>