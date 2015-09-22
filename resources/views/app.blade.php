<!doctype html>
<html lang="fr">

    <head>
        <meta charset="UTF-8"/>
        <title>Mobilizator</title>
        <!-- Latest compiled and minified CSS -->
        <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">-->

        <!-- Optional theme -->
        <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.5/cerulean/bootstrap.min.css">





        <!-- Jquery -->
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

        <!--Mooc specific styles & scripts -->
        <link rel="stylesheet" href="{{ asset('css/all.css') }}">
        <script src="{{ asset('js/mooc.js') }}"></script>

        <!-- CKeditor -->
        <script src="//cdn.ckeditor.com/4.5.3/standard/ckeditor.js"></script>


    </head>

    <body>

    @include('partials.nav')


        <div class="container">
            @yield('content')
        </div>

        @yield('footer')

    </body>

</html>
