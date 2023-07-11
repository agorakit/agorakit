<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">

    @include('partials.header')
</head>

<body>

    @unless (request()->get('embed'))
        @include('partials.nav')
    @endunless

    @include('partials.errors')

    <div class="container mt-4">
        @yield('content')
    </div>

   
    @include('partials.footer')

</body>

</html>
