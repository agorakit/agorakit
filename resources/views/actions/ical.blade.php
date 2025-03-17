<div class="my-5 d-flex gap-2 flex-wrap">

    <div class="mb-4">
        <a class="btn btn-outline-secondary" href="{{ action('GroupIcalController@index', $group) }}" target="_blank">
            <i class="far fa-calendar-alt me-2"></i>
            {{ trans('messages.public_ical_group_link') }}
        </a>
    </div>

    @auth
        <div>
            <a class="btn btn-outline-secondary" href="{{ URL::signedRoute('users.ical', ['user' => Auth::user()]) }}"
                target="_blank">
                <i class="far fa-calendar-alt me-2"></i>
                {{ trans('messages.personal_ical_group_link') }}
            </a>
        </div>
    @endauth
</div>
