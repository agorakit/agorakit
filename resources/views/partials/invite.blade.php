<div class="alert alert-info" role="alert">

    @guest
        <i class="fa fa-info-circle" aria-hidden="true"></i>
        {{ trans('messages.if_you_want_participate_in_this_group') }}
        <a href="{{ url('login') }}" up-layer="new">{{ trans('messages.you_login') }}</a>
        {{ trans('messages.or') }}
        <a href="{{ url('register') }}" up-layer="new">{{ trans('messages.you_register') }}</a>.
    @endguest

    @auth
        @if (Auth::user()->isCandidateOf($group))
            {{ trans('membership.group_candidate_info') }}
        @elseif (!Auth::user()->isMemberOf($group))
            <i class="fa fa-info-circle" aria-hidden="true"></i>
            {{ trans('messages.if_you_want_participate_in_this_group') }}
            <a href="{{ action('GroupMembershipController@store', $group) }}">
                {{ trans('messages.join_this_group') }}</a>
        @endif
    @endauth
</div>
