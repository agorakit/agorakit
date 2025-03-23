<div class="mt-6 d-flex gap-3 flex-wrap">
    <a href="{{ action('IcalController@index') }}" target="_blank">
        <i class="far fa-calendar-alt me-2"></i>
        {{ trans('messages.public_ical_group_link') }}
    </a>
    <div class="vr"></div>

    @auth
        <a href="{{ URL::signedRoute('users.ical', ['user' => Auth::user()]) }}" target="_blank">
            <i class="fas fa-user-lock me-2"></i>
            {{ trans('messages.personal_ical_group_link') }}
        </a>
        <div class="vr"></div>
    @endauth

    <a href="{{ route('actions.feed') }}">
        <i class="fas fa-rss me-2" target="_blank"></i>
        {{ trans('messages.rss') }}
    </a>
</div>
