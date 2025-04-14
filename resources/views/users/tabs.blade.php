<h1 class="mb-3">
    <a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
    {{ $user->name }} <em>({{ '@' . $user->username }})</em>
</h1>

<ul class="nav nav-pills mb-5 nav-centered">

    <li class="nav-item">
        <a class="nav-link @if (isset($tab) && $tab == 'profile') active @endif" href="{{ route('users.show', $user) }}">
            <i class="fa fa-user me-2"></i> {{ trans('messages.user_profile') }}
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link @if (isset($tab) && $tab == 'contact') active @endif" href="{{ route('users.contactform', $user) }}">
            <i class="fa fa-envelope-o me-2"></i> {{ trans('messages.contact_this_user') }}</a>
        </a>
    </li>

    @can('update', $user)
        <li class="nav-item">
            <a class="nav-link @if (isset($tab) && $tab == 'edit') active @endif" href="{{ route('users.edit', $user) }}">
                <i class="fa fa-pencil me-2"></i>
                {{ trans('messages.edit') }}
            </a>
        </li>
    @endcan

</ul>
