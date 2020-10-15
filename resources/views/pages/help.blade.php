@extends('app')

@section('content')

    <div class="tab_content ">

        @if (Auth::user()->isAdmin())
        <div class="mb-4 flex justify-end">
            <a up-target="body" href="{{ url('/admin/settings') }}" class="py-2 px-4 bg-gray-300 text-gray-600 rounded-full">
                <i class="fa fa-cog"></i> Edit this text
            </a>
            </div>
        @endif

        <div class="prose lg:prose-l">

            @if (strlen(setting('help_text')) > 5)
                {!! setting('help_text') !!}
            @else
                {!! setting('homepage_presentation', trans('documentation.intro')) !!}
            @endif
        </div>



    </div>

@endsection
