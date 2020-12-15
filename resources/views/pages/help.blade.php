@extends('app')

@section('content')

    <div class="tab_content ">

        @auth
            @if (Auth::user()->isAdmin())
                <div class="mb-4 flex justify-end">
                    <a up-target="body" href="{{ url('/admin/settings') }}"
                        class="btn btn-primary">
                        <i class="fa fa-cog"></i> Edit this text
                    </a>
                </div>
            @endif
        @endauth

        <div class="prose lg:prose-l">

            @if (strlen(setting('help_text')) > 5)
                {!! setting('help_text') !!}
            @else
                {!! setting('homepage_presentation', trans('documentation.intro')) !!}
            @endif
        </div>



    </div>

@endsection
