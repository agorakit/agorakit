<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1" name=viewport>

    @include('partials.header')
</head>

<body>

    <main up-main>

        @unless (request()->get('embed'))
            @include('partials.nav')
        @endunless

        <div class="sticky-messages">
            @include('partials.errors')
        </div>

        <div class="page">
            @if ($group->exists)
                @include('groups.tabs')
            @endif

            <div up-main="modal">
                @yield('content')
            </div>
        </div>

    </main>

    @include('partials.footer')

</body>

</html>
