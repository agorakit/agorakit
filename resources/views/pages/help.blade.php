@extends('app')

@section('content')

    <div class="tab_content p-4 prose">
        @if (strlen(setting('help_text')) > 5)
            {!! setting('help_text')!!}
        @else
            {!! setting('homepage_presentation', trans('documentation.intro')) !!}

            @auth
                <div class="alert alert-info">
                    Admins, you can set this page content in the settings area (Fill "Help page"). Curently showing the main presenation text here.
                </div>
            @endauth
        @endif

    </div>

@endsection
