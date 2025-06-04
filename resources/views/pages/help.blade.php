@extends('app')

@section('content')
    @auth
        @if (Auth::user()->isAdmin())
            <div class="mb-4 d-flex justify-end">
                <a class="btn btn-primary" href="{{ url('/admin/settings') }}">
                    <i class="fa fa-cog me-2"></i>{{ trans('messages.settings') }}
                </a>
            </div>
        @endif
    @endauth

    <div>
        @if (setting()->localized()->get('help_text'))
            {!! setting()->localized()->get('help_text') !!}
        @else
            {!! setting()->get('help_text', trans('documentation.intro')) !!}
        @endif
    </div>
@endsection
