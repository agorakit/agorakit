@extends('app')

@section('content')

    <div class="tab_content ">

        @auth
            @if (Auth::user()->isAdmin())
                <div class="mb-4 d-flex justify-end">
                    <a  href="{{ url('/admin/settings') }}"
                        class="btn btn-primary">
                        <i class="fa fa-cog"></i> Edit this text
                    </a>
                </div>
            @endif
        @endauth

        <div class="prose lg:prose-l">

            @if (setting()->localized()->get('help_text'))
            {!! setting()->localized()->get('help_text') !!}
            @else
            {!! setting()->get('help_text', trans('documentation.intro'))!!}
            @endif
        </div>



    </div>

@endsection
