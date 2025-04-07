<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1" name=viewport>

    @include('partials.header')
</head>

<body>

    <div up-main>
        @unless (request()->get('embed'))
            @include('partials.nav')
        @endunless

        <div class="sticky-messages">
            @include('partials.errors')
        </div>

        <div class="container mt-md-4 p-md-4 p-2" up-main="modal">
            @if (isset($context) && $context == 'group')
                @include('groups.tabs')
            @endif
            @if (isset($context) && $context == 'overview')
                @include('dashboard.tabs')
            @endif
            <main>
                @yield('content')
            </main>
        </div>
    </div>

    @include('partials.footer')

</body>

</html>
