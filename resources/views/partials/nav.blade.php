<nav
    class="z-50 flex px-1 py-1 bg-gray-700 md:sticky md:top-0 bottom-0 fixed w-full justify-around md:justify-start font-extrabold overflow-x-auto">

    <a up-follow up-cache="false" class=" text-gray-200 px-1 flex-col justify-center items-center hidden sm:flex"
        href="{{ route('index') }}">
        <img src="{{route('icon', 128)}}" class="w-12 h-12 mr-4 object-cover" />
    </a>

    <a up-follow up-cache="false" class=" text-gray-200 px-1 flex-col justify-center items-center flex"
        href="{{ route('index') }}">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
            </path>
        </svg>
        <span class="mx-2 md:inline text-xs">{{trans('Home')}}</span>
    </a>



    <a up-target="body" class=" text-gray-200 px-1 flex flex-col justify-center items-center "
        href="{{ action('GroupController@index') }}">
        <svg class="w-10 h-10 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="mx-2 md:inline text-xs">{{trans('messages.groups')}}</span>
    </a>

    @if (Auth::check())
    <a href="{{route('users.show', Auth::user())}}"
        class=" text-gray-200 px-1 flex flex-col justify-center items-center">
        <img src="{{route('users.cover', [Auth::user(), 'small'])}}" class="rounded-full w-10 h-10" />

        <span class="mx-2 md:inline text-xs">{{ Auth::user()->name }}</span>
    </a>
    @else
    <a href="{{route('login')}}" class=" text-gray-200 px-1 flex flex-col justify-center items-center">
        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z">
            </path>
        </svg>
        <span class="mx-2 md:inline text-xs">{{ trans('Login') }}</span>
    </a>
    @endif

</nav>