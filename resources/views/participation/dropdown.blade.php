@auth
    <div class="participate-dropdown">
        <button class="btn btn-primary dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" type="button" aria-haspopup="true" aria-expanded="false">
            @if (Auth::user()->isAttending($action))
                <i class="fa fa-calendar-check-o me-2"></i> {{ __('I will participate') }}
            @elseif (Auth::user()->isNotAttending($action))
                <i class="fa fa-calendar-times-o me-2"></i> {{ __('I will not participate') }}
            @else
                <i class="fa fa-question-circle-o me-2"></i> {{ __('I don\'t know yet') }}
            @endif
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

            <a class="dropdown-item" href="{{ route('groups.actions.participation.set', ['group' => $action->group, 'action' => $action, 'status' => 'yes']) }}"
                up-target="#participate-{{ $action->id }}" up-cache="false" up-history="false">
                {{ __('I will participate') }}
            </a>

            <a class="dropdown-item" href="{{ route('groups.actions.participation.set', ['group' => $action->group, 'action' => $action, 'status' => 'no']) }}"
                up-target="#participate-{{ $action->id }}" up-cache="false" up-history="false">
                {{ __('I will not participate') }}
            </a>

            <a class="dropdown-item" href="{{ route('groups.actions.participation.set', ['group' => $action->group, 'action' => $action, 'status' => 'maybe']) }}"
                up-target="#participate-{{ $action->id }}" up-cache="false" up-history="false">
                {{ __('I don\'t know yet') }}
            </a>

        </div>
    </div>
@endauth
