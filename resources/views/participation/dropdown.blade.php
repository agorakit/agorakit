@auth
<div class="participate-dropdown" id="participate-{{$action->id}}">
    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        @if (Auth::user()->isAttending($action))
        <i class="fa fa-calendar-check-o"></i> {{__('I will participate')}}
        @elseif (Auth::user()->isNotAttending($action))
        <i class="fa fa-calendar-times-o"></i> {{__('I will not participate')}}
        @else
        <i class="fa fa-question-circle-o"></i> {{__('I don\'t know yet')}}
        @endif
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

        <a up-target="#participate-{{$action->id}}" up-cache="false" up-history="false" class="dropdown-item"
            href="{{route('groups.actions.participation.set', ['group' => $action->group, 'action' => $action, 'status' => 'yes'])}}">
            {{__('I will participate')}}
        </a>

        <a up-target="#participate-{{$action->id}}" up-cache="false" up-history="false" class="dropdown-item"
            href="{{route('groups.actions.participation.set', ['group' => $action->group, 'action' => $action, 'status' => 'no'])}}">
            {{__('I will not participate')}}
        </a>

        <a up-target="#participate-{{$action->id}}" up-cache="false" up-history="false" class="dropdown-item"
            href="{{route('groups.actions.participation.set', ['group' => $action->group, 'action' => $action, 'status' => 'maybe'])}}">
            {{__('I don\'t know yet')}}
        </a>

    </div>
</div>
@endauth