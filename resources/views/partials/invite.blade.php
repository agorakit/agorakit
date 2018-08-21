@guest
  <div class="help" role="alert">
    <i class="fa fa-info-circle" aria-hidden="true"></i>
    {{trans('messages.if_you_want_participate_in_this_group')}}
    <a href="{{ url('login') }}">{{trans('messages.you_login')}}</a>
    {{trans('messages.or')}}
    <a href="{{url('register')}}">{{trans('messages.you_register')}}</a>.
  </div>
@endguest

@auth
  @if (!Auth::user()->isMemberOf($group))
  <div class="help" role="alert">
    <i class="fa fa-info-circle" aria-hidden="true"></i>
    {{trans('messages.if_you_want_participate_in_this_group')}}
    <a href="{{action('MembershipController@store', $group)}}">
      {{trans('messages.join_this_group')}}</a>
    </div>
  @endif
@endauth
