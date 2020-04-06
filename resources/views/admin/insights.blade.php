@extends('app')

@section('content')

    <div class="tab_content">



        @push('js')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
        @endpush


        <h1>{{trans('messages.insights')}}</h1>

        @foreach ($charts as $chart)
            <div style="height: 300px" class="mb-4">
                {!! $chart->container() !!}
            </div>
            {!! $chart->script() !!}

        @endforeach

    </div>

@endsection
