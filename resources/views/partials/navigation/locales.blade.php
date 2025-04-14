@if (\Config::has('app.locales') and setting('show_locales_inside_navbar', true))
    <!-- locales -->
    <div class="nav-item dropdown">
        <a aria-expanded="false" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
            <i class="fa fa-globe"></i>
        </a>

        <ul class="dropdown-menu">
            @foreach (\Config::get('app.locales') as $locale)
                @if (setting("show_locale_{$locale}", true))
                    <li>
                        <a class="dropdown-item locale-{{ $locale }}" href="{{ Request::url() }}?force_locale={{ $locale }}">
                            {{ strtoupper($locale) }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
@endif
