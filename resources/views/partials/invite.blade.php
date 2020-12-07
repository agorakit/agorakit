@guest
<div class="help" role="alert">
    <i class="fa fa-info-circle" aria-hidden="true"></i>
    {{trans('messages.if_you_want_participate_in_this_group')}}
    <a up-follow href="{{ url('login') }}">{{trans('messages.you_login')}}</a>
    {{trans('messages.or')}}
    <a up-follow href="{{url('register')}}">{{trans('messages.you_register')}}</a>.
</div>
@endguest

@auth
@if(Auth::user()->hasLevel(\App\Membership::CANDIDATE, $group))
<div class="help" role="alert">
    {{trans('membership.group_candidate_info')}}
</div>
@elseif (!Auth::user()->isMemberOf($group))
<div class="help" role="alert">
    <i class="fa fa-info-circle" aria-hidden="true"></i>
    {{trans('messages.if_you_want_participate_in_this_group')}}
    <a up-follow href="{{action('GroupMembershipController@store', $group)}}">
        {{trans('messages.join_this_group')}}</a>
</div>
@endif
@endauth