<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name=viewport content="width=device-width, initial-scale=1">
    <title>{{env('APP_NAME')}}</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.5/yeti/bootstrap.min.css">-->
    <!-- other candidates include simplex and united yeti and flatly and paper-->





    <!-- Font awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    @yield('css')

    <!-- mobilizator specific css-->
    {!! Html::style('/css/all.css?v6') !!}



    @yield('head')
</head>

<body>

    @include('partials.nav')

    <div class="container-fluid nav-margin-top">


        @if (Auth::guest())
            @include('partials.errors')
            @yield('content')
        @else


            <div class="row">
                <div class="col-lg-3">
                    @include('partials.sidebar')
                </div>

                <div class="col-lg-9">
                    @include('partials.errors')
                    @yield('content')
                </div>

            </div>
        @endif

        <div class="credits">{{trans('messages.made_with')}} <a href="https://github.com/philippejadin/Mobilizator">Mobilizator</a></div>
    </div>


    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    @yield('js')

    @yield('footer')



</body>
</html>
