@extends('app')

@section('content')

    <div class="mb-2">
        @include('dashboard.tabs')
    </div>

    <div class="d-flex flex-wrap justify-content-between mb-3">
        <form class="form-inline" role="search" method="GET" action="{{ route('users') }}" up-autosubmit up-delay="500"
            up-target=".users">
            <div class="input-group">
                <input class="form-control" name="search" type="text" value="{{ Request::get('search') }}"
                    aria-label="Search" placeholder="{{ __('Filter') }}...">

                <button class="btn btn-secondary" type="submit"><span class="fa fa-search"></span></button>

            </div>
        </form>

    </div>

    <div class="users">
        @if ($users)
            @foreach ($users as $user)
                @include('users.user')
            @endforeach
            {!! $users->render() !!}
        @else
            {{ trans('messages.nothing_yet') }}
        @endif
    </div>

@endsection
