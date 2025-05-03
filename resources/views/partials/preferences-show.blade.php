@auth
    <div class="dropdown">
        <a aria-expanded="false" aria-haspopup="true" class="btn btn-secondary-outline dropdown-toggle" data-bs-toggle="dropdown"
            id="dropdownMenuButton" type="button">
            <i class="fa fa-eye text-secondary"></i>
            <span class="text-secondary ms-2 d-none d-md-inline">
                @if (Auth::user()->getPreference('show', 'joined') == 'joined')
                    {{ __('Show my groups') }}
                @endif
                @if (Auth::user()->getPreference('show', 'joined') == 'all')
                    {{ __('Show all groups') }}
                @endif
                @if (Auth::user()->getPreference('show', 'joined') == 'admin')
                    {{ __('Show all groups (admin overview)') }}
                @endif
            </span>
        </a>
        <div aria-labelledby="dropdownMenuButton" class="dropdown-menu dropdown-menu-end">
            <a class="dropdown-item @if (Auth::user()->getPreference('show', 'joined') == 'joined') active @endif" href="?set_preference=show&value=joined">
                {{ __('Show my groups') }}
            </a>

            <a class="dropdown-item @if (Auth::user()->getPreference('show', 'joined') == 'all') active @endif" href="?set_preference=show&value=all">
                {{ __('Show all groups') }}
            </a>

            @if (Auth::user()->isAdmin())
                <a class="dropdown-item @if (Auth::user()->getPreference('show', 'joined') == 'admin') active @endif" href="?set_preference=show&value=admin">
                    {{ __('Show all groups (admin overview)') }}
                </a>
            @endif

        </div>
    </div>
@endauth
