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
            @include('partials.navigation.navbar')
        @endunless

        <div class="sticky-messages">
            @include('partials.errors')
        </div>

        <div class="container mt-md-4 p-md-4 p-2" up-main="modal">
            @include('partials.navigation.main')

            <main>
                @yield('content')

                <div class="mt-5">Context : {{ Context::get() }} </div>
                <div>Visible groups {{ Context::getVisibleGroups() }}</div>
            </main>
        </div>
    </div>

    @include('partials.footer')

</body>

</html>
