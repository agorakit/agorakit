@extends('app')

@section('content')
    <div class="mb-2">
        @include('dashboard.tabs')
    </div>

    <div class="d-flex flex-wrap gap-2">
        @include('partials.tags_filter')
        @include ('partials.preferences-show')
    </div>

    <div class="mt-4">
        @if ($files->count() > 0)
            @foreach ($files as $file)
                @include('files.file')
            @endforeach

            {{ $files->render() }}
        @else
            {{ trans('messages.nothing_yet') }}
        @endif

    </div>

@endsection
