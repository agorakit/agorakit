@extends('group')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>

    <h1>{{ trans('messages.insights') }}</h1>

    @foreach ($charts as $chart)
        <div class="mb-4" style="height: 300px">
            {!! $chart->container() !!}
        </div>
        {!! $chart->script() !!}
    @endforeach
@endsection
