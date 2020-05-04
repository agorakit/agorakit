@auth
  @if (Auth::user()->getPreference('show', 'my') == 'my')
    <div class="dropdown">
      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="far fa-eye"></i>
      </button>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
        <a href="?set_preference=show&value=my" class="dropdown-item active">  {{__('Show my groups')}}</a>
        <a href="?set_preference=show&value=all" class="dropdown-item">{{__('Show all groups')}}</a>
      </div>
    </div>

  @else
    <div class="dropdown">
      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="far fa-eye"></i>
      </button>
      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
        <a href="?set_preference=show&value=my" class="dropdown-item">{{__('Show my groups')}}</a>
        <a href="?set_preference=show&value=all" class="dropdown-item active">{{__('Show all groups')}}</a>
      </div>
    </div>

  @endif
@endauth
