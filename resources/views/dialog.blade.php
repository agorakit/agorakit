<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    @include('partials.header')
</head>

<body>

    <div class="page page-center">
        <div class="container container-tight p-4" up-main>
            @include('partials.errors')
            @yield('content')

        </div>

        @include('partials.footer')
    </div>

</body>

</html>
