@extends('app')

@section('content')

    <div class="tab_content">

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

    </div>

@endsection
