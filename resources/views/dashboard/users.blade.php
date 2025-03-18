@extends('app')

@section('content')

    <div class="mb-2">
        @include('dashboard.tabs')
    </div>

    <div class="d-flex flex-wrap justify-content-between mb-3">
        <form action="{{ route('users') }}" class="form-inline" method="GET" role="search" up-autosubmit up-target=".users"
            up-watch-delay="500">
            <div class="input-group">
                <input aria-label="Search" class="form-control" name="search" placeholder="{{ __('Filter') }}..."
                    type="text" value="{{ Request::get('search') }}">

                <button class="btn btn-secondary" type="submit"><span class="fa fa-search"></span></button>

            </div>
        </form>

    </div>

    <div class="mt-4 row row-cards gap-3">
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
