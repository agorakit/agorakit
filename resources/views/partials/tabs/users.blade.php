<ul class="nav nav-underline mb-5 nav-centered">

    <li class="nav-item">
        <a class="nav-link @if (isset($tab) && $tab == 'profile') active @endif" href="{{ route('users.show', $user) }}">
            <i class="fa fa-user me-2"></i> <span class="d-none d-sm-inline">{{ t('User profile') }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link @if (isset($tab) && $tab == 'contact') active @endif" href="{{ route('users.contactform', $user) }}">
            <i class="fa fa-envelope-o me-2"></i> <span class="d-none d-sm-inline">{{ t('Contact this user') }}</span>
        </a>
        </a>
    </li>

    @can('update', $user)
        <li class="nav-item">
            <a class="nav-link @if (isset($tab) && $tab == 'edit') active @endif" href="{{ route('users.edit', $user) }}">
                <i class="fa fa-pencil me-2"></i>
                <span class="d-none d-sm-inline">{{ t('Edit') }}</span>
            </a>
        </li>
    @endcan

</ul>
