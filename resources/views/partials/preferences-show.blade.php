@auth
  @if (Auth::user()->getPreference('show', 'my') == 'my')

    <div class="dropdown">
      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{__('Show my groups')}}
      </button>
      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a href="?set_preference=show&value=my" class="dropdown-item">{{trans('messages.my_groups')}}</a>
        <a href="?set_preference=show&value=all" class="dropdown-item">{{trans('messages.all_groups')}}</a>
      </div>
    </div>

  @else
    <div class="dropdown">
      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{__('Show all groups')}}
      </button>
      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a href="?set_preference=show&value=my" class="dropdown-item">{{trans('messages.my_groups')}}</a>
        <a href="?set_preference=show&value=all" class="dropdown-item">{{trans('messages.all_groups')}}</a>
      </div>
    </div>

  @endif
@endauth
