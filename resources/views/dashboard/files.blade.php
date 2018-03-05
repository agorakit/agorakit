@extends('app')


@section('content')
    <div class="page_header">
        <h1><a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.files') }}</h1>
    </div>



    <div class="files mt-5">
        @forelse( $files as $file )
            @include('files.file')
        @empty
            {{trans('messages.nothing_yet')}}
        @endforelse

        {!! $files->render() !!}
    </div>
    

@endsection
