  <!-- User profile -->
  @auth
      <div class="nav-item dropdown">
          <a aria-expanded="false" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
              {{ trans('messages.profile') }}
          </a>

          <div class="dropdown-menu dropdown-menu-end" role="menu">
              <a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}"><i class="fa fa-btn fa-user me-2"></i>
                  {{ trans('messages.profile') }}</a>
              <a class="dropdown-item" href="{{ route('users.edit', Auth::user()) }}"><i class="fa fa-btn fa-user-edit me-2"></i>
                  {{ trans('messages.edit_my_profile') }}</a>

              <a class="dropdown-item" href="{{ url('/logout') }}"
                  onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                  <i class="fa fa-btn fa-sign-out  me-2"></i> {{ trans('messages.logout') }}
              </a>

              <form action="{{ url('/logout') }}" id="logout-form" method="POST" style="display: none;">
                  @csrf
                  @honeypot
              </form>

          </div>

      </div>
  @endauth
