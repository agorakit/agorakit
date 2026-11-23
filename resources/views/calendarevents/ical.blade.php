<div class="mt-6 d-flex gap-3 flex-wrap">
    <a class="" href="{{ action('GroupIcalController@index', $group) }}" target="_blank">
        <i class="far fa-calendar-alt me-1"></i>
        {{ trans('Public iCal link') }}
    </a>

    @auth
        <div class="vr"></div>
        <a class="" href="{{ URL::signedRoute('users.ical', ['user' => Auth::user()]) }}" target="_blank">
            <i class="fas fa-user-lock me-1"></i>
            {{ trans('Private iCal link (do not share)') }}
        </a>
    @endauth
</div>
