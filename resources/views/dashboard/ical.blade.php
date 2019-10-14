<div class="mt-5 d-md-flex justify-content-between">

  <div class="mb-2">
    <a class="btn btn-secondary" href="{{action('IcalController@index')}}">
      <i class="far fa-calendar-alt"></i>
      Public iCal feed
    </a>
  </div>

  @auth
    <div class="mb-2">
      <a class="btn btn-secondary" href="{{URL::signedRoute('users.ical', ['user' => Auth::user()])}}">
        <i class="fas fa-user-lock"></i>
        Personalized iCal feed of your groups
      </a>
    </div>
  @endauth
</div>
