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

    <main>
        <div class="container mt-4">
            @include('partials.errors')
            @yield('content')
        </div>
    </main>

    @include('partials.footer')

</body>

</html>
