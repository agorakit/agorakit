<!doctype html>
<html lang="fr">

    <head>
        <meta charset="UTF-8"/>
        <title>Mobilizator</title>
        <!-- Bootstrap -->

        <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.5/yeti/bootstrap.min.css">

        <!-- Font awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">


        <!-- Jquery -->
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

        <!-- mobilizator specific css-->
        {!! HTML::style('/css/all.css') !!}



        <!-- CKeditor -->
        <script src="//cdn.ckeditor.com/4.5.3/standard/ckeditor.js"></script>

        @yield('head')

    </head>

    <body>

    @include('partials.nav')

    @if ( Session::has('message') )
    <div class="container">
    <div class="alert alert-info">
        <h3>{{ Session::get('message') }}</h3>
    </div>
  </div>
    @endif

            <div class="container">
            @yield('content')
        </div>

        @yield('footer')

    </body>

</html>
