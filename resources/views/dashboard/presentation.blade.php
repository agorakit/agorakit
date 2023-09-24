@extends('app')

@section('content')
    <div class="mb-2">
        @include('dashboard.tabs')
    </div>

    @guest
        <div class="d-flex gap-2 flex-wrap justify-content-end mb-4">
            <a class="btn btn-primary" href="{{ url('login') }}" up-layer="new">
                {{ trans('messages.login') }}
            </a>

            @can('create', App\User::class)
                <a class="btn btn-primary" href="{{ url('register') }}" up-layer="new">
                    {{ trans('messages.register') }}
                </a>
            @endcan
        </div>
    @endguest

    <div class="mb-3">
        @auth
            @if (setting()->localized()->get('homepage_presentation_for_members'))
                {!! setting()->localized()->get('homepage_presentation_for_members') !!}
            @else
                {!! setting()->get('homepage_presentation_for_members') !!}
            @endif
        @else
            @if (setting()->localized()->get('homepage_presentation'))
                {!! setting()->localized()->get('homepage_presentation') !!}
            @else
                {!! setting()->get('homepage_presentation', trans('documentation.intro')) !!}
            @endif
        @endauth
    </div>
@endsection
