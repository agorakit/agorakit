@extends('app')

@section('content')

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

    <h1>
        {{ setting('name') }}
    </h1>

    <div class="mb-3">
        @if (setting()->localized()->get('homepage_presentation'))
            {!! setting()->localized()->get('homepage_presentation') !!}
        @else
            {!! setting()->get('homepage_presentation', trans('documentation.intro')) !!}
        @endif
    </div>

    @if ($groups)
        <div class="groups">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach ($groups as $group)
                    @include('groups.group')
                @endforeach
            </div>
            {!! $groups->links() !!}

        </div>
    @endif

@endsection
