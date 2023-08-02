@auth
<div class="dropdown">
  <a class="dropdown-toggle cursor-pointer text-gray-700" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
    aria-haspopup="true" aria-expanded="false">
    @if (Auth::user()->getPreference('show', 'my') == 'my')
    {{__('Show my groups')}}
    @elseif (Auth::user()->getPreference('show', 'my') == 'all')
    {{__('Show all groups')}}
    @elseif (Auth::user()->getPreference('show', 'my') == 'admin')
    {{__('Show all groups (admin overview)')}}
    @endif

  </a>
  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
    <a up-follow href="?set_preference=show&value=my"
      class="dropdown-item @if (Auth::user()->getPreference('show', 'my') == 'my') active @endif">
      {{__('Show my groups')}}
    </a>

    <a up-follow href="?set_preference=show&value=all"
      class="dropdown-item @if (Auth::user()->getPreference('show', 'my') == 'all') active @endif">
      {{__('Show all groups')}}
    </a>

    @if (Auth::user()->isAdmin())
    <a up-follow href="?set_preference=show&value=admin"
      class="dropdown-item @if (Auth::user()->getPreference('show', 'my') == 'admin') active @endif">
      {{__('Show all groups (admin overview)')}}
    </a>
    @endif

  </div>
</div>
@endauth