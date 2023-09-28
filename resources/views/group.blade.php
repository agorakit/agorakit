<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">

    @include('partials.header')
</head>

<body>

    <div up-main>
        @unless (request()->get('embed'))
            @include('partials.nav')
        @endunless

        @include('partials.errors')

        <div class="container mt-md-4 p-md-4 p-2">
            @include('groups.tabs')

            <div class="mt-md-4 mt-2 content" up-main="modal">
                @yield('content')
            </div>
        </div>

    </div>

    @include('partials.footer')

</body>

</html>
