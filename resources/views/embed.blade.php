<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name=viewport content="width=device-width, initial-scale=1">
    <title>{{config('app.name')}}</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.5/yeti/bootstrap.min.css">-->
    <!-- other candidates include simplex and united yeti and flatly and paper-->





    <!-- Font awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    @yield('css')


    @yield('head')
</head>

<body>




    @include('partials.errors')
    @yield('content')




    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    @yield('js')

    @yield('footer')



</body>
</html>
