@extends('app')

@section('content')

    <div class="d-flex flex-wrap justify-content-between">
        <h1 class="mb-2">
            <a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.users') }}
        </h1>

        <form class="form-inline" role="search" method="GET" action="{{ route('users') }}" up-autosubmit up-delay="500" up-target=".users" up-reveal="false">
            <div class="input-group">
                <input class="form-control" name="search" type="text" value="{{ Request::get('search') }}" aria-label="Search" placeholder="{{ __('Filter') }}...">

                <button class="btn btn-secondary" type="submit"><span class="fa fa-search"></span></button>

            </div>
        </form>

    </div>

    <div class="mb-4">
        @include ('partials.preferences-show')
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
