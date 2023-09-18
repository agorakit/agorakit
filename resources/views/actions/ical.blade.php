<div class="my-5 sm:flex justify-content-between">
  
  <div class="mb-4">
  <a class="btn btn-secondary" href="{{action('GroupIcalController@index', $group)}}">
    <i class="far fa-calendar-alt"></i>
    {{trans('action.public_ical_group_link')}}
  </a>
  </div>

  @auth
  <div>
    <a class="btn btn-secondary" href="{{URL::signedRoute('users.ical', ['user' => Auth::user()])}}">
      <i class="far fa-calendar-alt"></i>
      {{trans('action.personal_ical_group_link')}}
    </a>
    </div>
  @endauth
</div>
