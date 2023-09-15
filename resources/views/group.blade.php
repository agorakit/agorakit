<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name=viewport content="width=device-width, initial-scale=1">

    @include('partials.header')
</head>

<body>

    <div>
        @unless (request()->get('embed'))
            @include('partials.nav')
        @endunless

        <div class="container mt-4 p-4">
            @include('groups.tabs')

            <div class="mt-4 content" up-main="modal">
                @include('partials.errors')
                @yield('content')
            </div>
        </div>

    </div>

    @include('partials.footer')

</body>

</html>
