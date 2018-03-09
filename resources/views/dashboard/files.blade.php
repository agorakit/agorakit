@extends('app')


@section('content')
    <div class="page_header d-flex mb-3">
        <h1><a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.files') }}</h1>
    </div>

    @include('partials.tags_filter')


    <div class="files mt-4">
        @forelse( $files as $file )
            @include('files.file')
        @empty
            {{trans('messages.nothing_yet')}}
        @endforelse

        {!! $files->render() !!}
    </div>


@endsection
