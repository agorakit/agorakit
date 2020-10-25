<div class="my-5 sm:flex justify-between">
  
  <div class="mb-4">
  <a class="bg-gray-700 text-gray-100 rounded-full py-2 px-3 text-xs sm:text-base" href="{{action('GroupIcalController@index', $group)}}">
    <i class="far fa-calendar-alt"></i>
    Public iCal feed of this group
  </a>
  </div>

  @auth
  <div>
    <a class="bg-gray-700 text-gray-100 rounded-full py-2 px-3 text-xs sm:text-base" href="{{URL::signedRoute('users.ical', ['user' => Auth::user()])}}">
      <i class="far fa-calendar-alt"></i>
      Personalized iCal feed of your groups
    </a>
    </div>
  @endauth
</div>
