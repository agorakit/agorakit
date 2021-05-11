<div class="flex items-start py-3 border-b border-gray-300 hover:bg-gray-100">
    <a up-follow href="{{ route('groups.actions.show', [$action->group, $action]) }}">
        <div
            class="border-gray-400 text-gray-600 bg-gray-200 border-2 flex-shrink-0 flex flex-col items-center justify-center h-12 w-12 rounded-lg mx-1">
            <div class="text-xl -mb-2 text-gray-800">{{ $action->start->format('d') }}</div>
            <div class="text-sm">{{ $action->start->format('M') }}</div>
        </div>
    </a>


    <div up-expand class="ml-3 flex-grow">

        <div class="text-gray-800 overflow-ellipsis overflow-hidden h-5">
            <a up-follow href="{{ route('groups.actions.show', [$action->group, $action]) }}">
                {{ $action->name }}
            </a>
        </div>
        @if ($action->attending->count() > 0)
        <div class="text-gray-600 text-xs">
            <i class="fa fa-users"></i> {{ $action->attending->count() }}
            {{ trans('participants') }}
        </div>
        @endif

        <div class="text-gray-600 text-xs">
            <i class="fa fa-clock-o"></i> {{ $action->start->format('H:i') }} -
            {{ $action->stop->format('H:i') }}
        </div>

        @if ($action->location)
        <div class="text-gray-600 text-xs overflow-ellipsis overflow-hidden h-4">
            <i class="fa fa-map-marker"></i> {{ $action->location }}
        </div>
        @endif



    </div>




    @can ('participate', $action)
    <div class="participate-dropdown" id="participate-{{$action->id}}">
        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            @if (Auth::user()->isAttending($action))
            <i class="fa fa-calendar-check-o"></i>
            @elseif (Auth::user()->isNotAttending($action))
            <i class="fa fa-calendar-times-o"></i>
            @else
            <i class="fa fa-question-circle-o"></i>
            @endif
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

            <a up-follow up-cache="false" up-history="false" class="dropdown-item"
                href="{{route('groups.actions.participation.set', ['group' => $action->group, 'action' => $action, 'status' => 'yes'])}}">
                {{__('I will participate')}}
            </a>

            <a up-follow up-cache="false" up-history="false" class="dropdown-item"
                href="{{route('groups.actions.participation.set', ['group' => $action->group, 'action' => $action, 'status' => 'no'])}}">
                {{__('I will not participate')}}
            </a>



            <a up-follow up-cache="false" up-history="false" class="dropdown-item"
                href="{{route('groups.actions.participation.set', ['group' => $action->group, 'action' => $action, 'status' => 'maybe'])}}">
                {{__('I don\'t know yet')}}
            </a>

        </div>
    </div>
    @endcan


</div>