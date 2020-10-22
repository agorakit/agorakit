@extends('app')

@section('content')


    <div class="flex justify-end">
        @include ('partials.preferences-show')
    </div>


    <div class="flex justify-between my-4 items-center">
        <form up-target="body" class="form-inline my-2 my-lg-0" role="search" action="{{ url('search') }}" method="get">
            <div class="input-group">
                <input
                    class="shadow-md appearance-none border rounded-full py-2 px-3 w-32 sm:w-56 text-sm text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    type="text" name="query" placeholder="{{ trans('messages.search') }}..." aria-label="Search">
            </div>
        </form>


        <a class="bg-gray-700 text-gray-100 rounded-full shadow-md text-sm h-10 px-4 flex items-center"
            href="{{ route('discussions.create') }}">
            <i class="fas fa-pencil-alt"></i>
            <span class="hidden md:inline ml-2">{{ trans('discussion.create_one_button') }}</span>
        </a>
    </div>


    @if ($discussions->count() > 0)
        <div class="">
            @foreach ($discussions as $discussion)
                @include('discussions.discussion')
            @endforeach
        </div>
    @else
        {{ trans('messages.nothing_yet') }}
    @endif



    @if ($actions->count() > 0)
        <div class="my-8">
            @foreach ($actions as $action)
                @include('actions.action')
            @endforeach
        </div>
    @else
        {{ trans('messages.nothing_yet') }}
    @endif










@endsection
