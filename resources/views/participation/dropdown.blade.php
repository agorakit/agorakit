@auth
    <div class="participate-dropdown">
        <button aria-expanded="false" aria-haspopup="true" class="btn btn-outline-primary btn-sm  dropdown-toggle"
            data-bs-toggle="dropdown" id="dropdownMenuButton" type="button">
            @if (Auth::user()->isAttending($action))
                <i class="fa fa-calendar-check-o me-2"></i> {{ __('I will participate') }}
            @elseif (Auth::user()->isNotAttending($action))
                <i class="fa fa-calendar-times-o me-2"></i> {{ __('I will not participate') }}
            @else
                <i class="fa fa-question-circle-o me-2"></i> {{ __('I don\'t know yet') }}
            @endif
        </button>
        <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
            <a class="dropdown-item"
                href="{{ route('groups.actions.participation.set', ['group' => $action->group, 'action' => $action, 'status' => 'yes']) }}"
                up-cache="false" up-history="false" up-target="#participate-{{ $action->id }}">
                {{ __('I will participate') }}
            </a>

            <a class="dropdown-item"
                href="{{ route('groups.actions.participation.set', ['group' => $action->group, 'action' => $action, 'status' => 'no']) }}"
                up-cache="false" up-history="false" up-target="#participate-{{ $action->id }}">
                {{ __('I will not participate') }}
            </a>

            <a class="dropdown-item"
                href="{{ route('groups.actions.participation.set', ['group' => $action->group, 'action' => $action, 'status' => 'maybe']) }}"
                up-cache="false" up-history="false" up-target="#participate-{{ $action->id }}">
                {{ __('I don\'t know yet') }}
            </a>

        </div>
    </div>
@endauth
