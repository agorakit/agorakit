@auth
    @if (Auth::user()->getPreference('show', 'my') == 'my')
        <div class="btn-group mt-3 mb-4" role="group">
            <a href="?set_preference=show&value=my" class="btn btn-primary">{{trans('messages.my_groups')}}</a>
            <a href="?set_preference=show&value=all" class="btn btn-outline-primary">{{trans('messages.all_groups')}}</a>
        </div>
    @else
        <div class="btn-group mt-3 mb-4" role="group">
            <a href="?set_preference=show&value=my" class="btn btn-outline-primary">{{trans('messages.my_groups')}}</a>
            <a href="?set_preference=show&value=all" class="btn btn-primary">{{trans('messages.all_groups')}}</a>
        </div>
    @endif
@endauth
