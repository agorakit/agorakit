@extends('app')

@section('content')

    <h1><a href="{{ route('index') }}" up-follow><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
        {{ trans('messages.files') }}</h1>

    <div class="flex">
        @include('partials.tags_filter')
        <div class="ml-auto">
            @include ('partials.preferences-show')
        </div>
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
