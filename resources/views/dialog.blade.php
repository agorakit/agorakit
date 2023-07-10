<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">
    @include('partials.header')
</head>

<body>

    <div class="page page-center">
        <div class="dialog container container-tight py-4">
            <div class="card card-md">
                <div class="card-body">
                    @yield('content')
                </div>
                @include('partials.errors')
            </div>

            @include('partials.footer')
        </div>
    </div>

</body>

</html>
