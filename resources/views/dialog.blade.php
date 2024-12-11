<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    @include('partials.header')
</head>

<body>
    <main up-main>
        
            <div up-main="modal">
                @include('partials.errors')
                @yield('content')
            </div>
        
        @include('partials.footer')
    </main>

</body>

</html>
