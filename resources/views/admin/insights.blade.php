@extends('app')

@section('content')

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


        <h1>{{trans('messages.insights')}}</h1>

        @foreach ($charts as $chart)
            {!! $chart->html() !!}
        @endforeach

    </div>

@endsection
