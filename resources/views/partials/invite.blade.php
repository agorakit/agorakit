    @guest
        <div class="alert alert-info" role="alert">
            <i class="fa fa-info-circle" aria-hidden="true"></i>
            {{ trans('messages.if_you_want_participate_in_this_group') }}
            <a href="{{ url('login') }}" up-layer="new">{{ trans('messages.you_login') }}</a>
            {{ trans('messages.or') }}
            <a href="{{ url('register') }}" up-layer="new">{{ trans('messages.you_register') }}</a>.
        </div>
    @endguest

    @auth
        @if (Auth::user()->isCandidateOf($group))
            <div class="alert alert-info" role="alert">
                {{ trans('membership.group_candidate_info') }}
            </div>
        @elseif (!Auth::user()->isMemberOf($group))
            <div class="alert alert-info" role="alert">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
                {{ trans('messages.if_you_want_participate_in_this_group') }}
                <a href="{{ action('GroupMembershipController@store', $group) }}">
                    {{ trans('messages.join_this_group') }}</a>
            </div>
        @endif
    @endauth
