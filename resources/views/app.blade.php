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
            <div class="d-flex flex-column flex-sm-row">
                @if (Context::isGroup() || Context::isOverview())
                    <div class="flex-grow-1">
                        @include('partials.context')
                    </div>
                @endif

                <div class="ms-auto d-flex">
                    @auth
                        <div class="me-3">
                            <h3> @include('partials.user')</h3>
                        </div>
                    @endauth

                    <div>
                        <h3> @include('partials.locales')</h3>
                    </div>
                </div>
            </div>

            @if (Context::isGroup())
                @include('groups.tabs')
            @endif
            @if (Context::isOverview())
                @include('dashboard.tabs')
            @endif
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
