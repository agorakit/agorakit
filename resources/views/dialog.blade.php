<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1" name=viewport>
    @include('partials.header')
</head>

<body>
    <main class="page page-center" up-main>
        <div class="container container-tight p-4">
            <div up-main="modal">
                @include('partials.errors')
                @yield('content')
            </div>
        </div>
        @include('partials.footer')
    </main>

</body>

</html>
