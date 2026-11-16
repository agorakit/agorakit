    @guest
        <div class="alert alert-info" role="alert">
            <i class="fa fa-info-circle" aria-hidden="true"></i>
            {{ trans('If you want to participate in this group') }}
            <a href="{{ url('login') }}" up-layer="new">{{ trans('login here') }}</a>
            {{ trans('or') }}
            <a href="{{ url('register') }}" up-layer="new">{{ trans('register here') }}</a>.
        </div>
    @endguest

    @auth
        @if (Auth::user()->isCandidateOf($group))
            <div class="alert alert-info" role="alert">
                {{ trans('You have asked to join this group. You must now wait for an admin\'s response.') }}
            </div>
        @elseif (!Auth::user()->isMemberOf($group))
            <div class="alert alert-info" role="alert">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
                {{ trans('If you want to participate in this group') }}
                <a href="{{ action('GroupMembershipController@store', $group) }}">
                    {{ trans('Join this group') }}</a>
            </div>
        @endif
    @endauth
