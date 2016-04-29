@if (auth::guest())
  <div class="alert alert-info" role="alert">
    <i class="fa fa-info-circle" aria-hidden="true"></i>
    {{trans('messages.if_you_want_participate_in_this_group')}}
    <a href="{{ url('login') }}">{{trans('messages.you_login')}}</a>
    {{trans('messages.or')}}
    <a href="{{url('register')}}">{{trans('messages.you_register')}}</a>.
  </div>
@elseif (!auth::user()->isMemberOf($group))
  <div class="alert alert-info" role="alert">
    <i class="fa fa-info-circle" aria-hidden="true"></i>
    <a href="{{action('MembershipController@join', $group)}}"><i class="fa fa-sign-in" aria-hidden="true"></i>
      {{trans('messages.join_this_group')}}</a> {{trans('messages.if_you_want_participate_in_this_group')}}
    </div>
  @endif
