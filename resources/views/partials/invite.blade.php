<div class="alert alert-success" role="alert">

    @guest
        <div class="text-secondary">
            <i class="fa fa-info-circle" aria-hidden="true"></i>
            {{ trans('messages.if_you_want_participate_in_this_group') }}
            <a href="{{ url('login') }}" up-follow>{{ trans('messages.you_login') }}</a>
            {{ trans('messages.or') }}
            <a href="{{ url('register') }}" up-follow>{{ trans('messages.you_register') }}</a>.
        </div>
    @endguest

    @auth
        @if (Auth::user()->isCandidateOf($group))
            <div class="text-secondary">
                {{ trans('membership.group_candidate_info') }}
            </div>
        @elseif (!Auth::user()->isMemberOf($group))
            <div class="text-secondary">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
                {{ trans('messages.if_you_want_participate_in_this_group') }}
                <a href="{{ action('GroupMembershipController@store', $group) }}" up-follow>
                    {{ trans('messages.join_this_group') }}</a>
            </div>
        @endif
    @endauth
</div>
