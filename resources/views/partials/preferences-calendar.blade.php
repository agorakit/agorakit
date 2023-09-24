@auth
    @if (Auth::user()->getPreference('calendar', 'grid') == 'grid')
        <div class="btn-group" role="group">
            <a  href="?set_preference=calendar&value=grid" class="btn btn-primary"><i class="fa fa-calendar me-2"></i> {{trans('messages.grid')}}</a>
            <a  href="?set_preference=calendar&value=list" class="btn btn-outline-primary"><i class="fa fa-list me-2"></i> {{trans('messages.list')}}</a>
        </div>
    @else
        <div class="btn-group" role="group">
            <a  href="?set_preference=calendar&value=grid" class="btn btn-outline-primary"><i class="fa fa-calendar me-2"></i> {{trans('messages.grid')}}</a>
            <a  href="?set_preference=calendar&value=list" class="btn btn-primary"><i class="fa fa-list me-2"></i> {{trans('messages.list')}}</a>
        </div>

    @endif
@endauth
