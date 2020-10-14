@extends('app')

@section('content')


    <div class="mx-auto md:w-10/12 lg:w-8/12 max-w-5xl bg-white sm:shadow-xl md:my-5 sm:rounded-lg">

        <div class="flex justify-between mx-4 py-6">

            <input
                class="shadow appearance-none border rounded-full px-3 w-32 sm:w-56 text-sm text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                id="username" type="text" placeholder="Search..." />


            <a class="bg-gray-700 text-gray-100 inline-block rounded-full px-4 py-1 sm:py-2 sticky shadow-lg text-sm"
                href="{{ route('discussions.create') }}">{{ trans('discussion.create_one_button') }}</a>
        </div>


        @if ($discussions->count() > 0)
            <div class="divide-y divide-gray-300">
                @foreach ($discussions as $discussion)
                    @include('discussions.discussion')
                @endforeach
            </div>
        @else
            {{ trans('messages.nothing_yet') }}
        @endif



        @if ($actions->count() > 0)
            <div class="divide-y divide-gray-300 my-8">
                @foreach ($actions as $action)
                    @include('actions.action')
                @endforeach
            </div>
        @else
            {{ trans('messages.nothing_yet') }}
        @endif



    </div>









@endsection
