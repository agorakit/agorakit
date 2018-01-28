<div class="page_header">
    <h1><a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
        {{ $user->name }}
    </h1>
</div>


<ul class="nav nav-tabs">

    <li class="nav-item">
        <a href="{{ route('users.show', $user->id) }}" class="nav-link @if (isset($tab) && ($tab == 'profile')) active @endif">
            <i class="fa fa-user"></i> {{ trans('messages.user_profile') }}
        </a>
    </li>



    <li class="nav-item">
        <a href="{{route('users.contactform', $user->id)}}" class="nav-link @if (isset($tab) && ($tab == 'contact')) active @endif">
            <i class="fa fa-envelope-o"></i> {{trans('messages.contact_this_user')}}</a>
        </a>
    </li>

    @can('update', $user)
        <li class="nav-item">
            <a href="{{ route('users.edit', $user->id) }}" class="nav-link @if (isset($tab) && ($tab == 'edit')) active @endif">
                <i class="fa fa-pencil"></i>
                {{trans('messages.edit')}}
            </a>
        </li>
    @endcan

</ul>
