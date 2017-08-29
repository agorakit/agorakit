@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        @push('css')
            {!! Charts::styles() !!}
        @endpush

        @push('js')
            {!! Charts::scripts() !!}

            @foreach ($charts as $chart)
                {!! $chart->script() !!}
            @endforeach

        @endpush


        @foreach ($charts as $chart)
            {!! $chart->html() !!}
        @endforeach
        


    @endsection
