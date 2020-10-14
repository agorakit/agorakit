<nav
    class="z-50 flex px-3 py-1 bg-gray-700 md:sticky md:top-0 bottom-0 fixed w-full justify-around md:justify-start font-extrabold overflow-x-auto">

    <a up-follow up-cache="false" class=" text-gray-200 px-1 flex flex-col justify-center items-center"
        href="{{ route('index') }}">
        <img src="{{route('icon', 128)}}" class="w-10 h-10 object-cover" />
        <span class="mx-2 md:inline text-xs">{{trans('Home')}}</span>
    </a>

    <button class=" text-gray-200 px-1 flex flex-col justify-center items-center ">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        <span class="mx-2 md:inline text-xs">Home</span>
    </button>



    <a up-target="body" class=" text-gray-200 px-1 flex flex-col justify-center items-center "
        href="{{ action('GroupController@index') }}">
        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"></path></svg>
        <span class="mx-2 md:inline text-xs">{{trans('messages.groups')}}</span>
    </a>

    <!--
    <button class=" text-gray-200 px-1 flex flex-col justify-center items-center ">
        <i class="ri-compass-3-line text-4xl -mb-2"></i>
        <span class="mx-2 md:inline text-xs">Explore</span>
    </button>


    <button class=" text-gray-200 px-1 flex flex-col justify-center items-center ">
        <i class="ri-notification-3-line text-4xl -mb-2"></i>
        <span class="mx-2 md:inline text-xs">Notifications</span>
    </button>


    <button class=" text-gray-200 px-1 flex flex-col justify-center items-center ">
        <i class="ri-user-3-line text-4xl -mb-2 w-10 h-10"></i>
        <span class="mx-2 md:inline text-xs">Profile</span>
    </button>
-->

    <a href="{{route('users.show', Auth::user())}}"
        class=" text-gray-200 px-1 flex flex-col justify-center items-center " data-toggle="dropdown" role="button"
        aria-expanded="false">
        <img src="{{route('users.cover', [Auth::user(), 'small'])}}" class="rounded-full w-10 h-10" />

        <span class="mx-2 md:inline text-xs">{{ Auth::user()->name }}</span>
    </a>
</nav>
