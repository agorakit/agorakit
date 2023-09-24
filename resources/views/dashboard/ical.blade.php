<div class="mt-5 d-flex gap-2 flex-wrap">

    <div class="mb-2">
        <a class="btn btn-secondary" href="{{ action('IcalController@index') }}">
            <i class="far fa-calendar-alt me-2"></i>
            Public iCal feed
        </a>
    </div>

    @auth
        <div class="mb-2">
            <a class="btn btn-secondary" href="{{ URL::signedRoute('users.ical', ['user' => Auth::user()]) }}">
                <i class="fas fa-user-lock me-2"></i>
                Personalized iCal feed of your groups
            </a>
        </div>
    @endauth

    <div class="text-secondary">
        <a class="btn btn-secondary" href="{{ route('actions.feed') }}"><i class="fas fa-rss me-2"></i> RSS</a>
    </div>

</div>
