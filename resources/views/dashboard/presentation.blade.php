@extends('app')

@section('content')
    @guest
        <div class="d-flex gap-2 flex-wrap justify-content-end mb-4">
            <a class="btn btn-primary" href="{{ url('login') }}" up-layer="new">
                {{ trans('messages.login') }}
            </a>

            @can('create', Agorakit\User::class)
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
                {!! setting()->get('homepage_presentation') !!}
            @endif
        @endauth
    </div>

    @if ($groups)
        <div class="groups">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
                @foreach ($groups as $group)
                    @include('groups.group')
                @endforeach
            </div>
            {!! $groups->links() !!}

        </div>
    @endif
@endsection
