<div class="mt-5">
  <a class="btn btn-primary" href="{{action('GroupIcalController@index', $group)}}">
    <i class="far fa-calendar-alt"></i>
    Public iCal feed of this group
  </a>

  @auth
    <a class="btn btn-primary" href="{{URL::signedRoute('users.ical', ['user' => Auth::user()])}}">
      <i class="far fa-calendar-alt"></i>
      Personalized iCal feed of your groups
    </a>
  @endauth
</div>
