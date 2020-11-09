@extends('app')

@section('content')


@if(setting('homepage_presentation_for_members', false))

    <div class="overflow-y-auto border items-start  shadow p-3 mb-4 rounded-lg bg-gray-200 text-sm sm:text-base flex"
        style="max-height: 10em;">
        <i class="fa fa-info-circle text-4xl mr-3 text-gray-500 hidden sm:block"></i>
        <div class="text-gray-600 flex-grow -mb-3 mt-1">

        @if(Auth::user()->isAdmin())
            <a up-follow href="{{ url('/admin/settings') }}"
                        class="ml-3 mb-3 py-2  px-4 bg-gray-100 text-gray-600 rounded-lg shadow inline-block float-right">
                        <i class="fa fa-cog"></i> 
                        <span class="hidden sm:inline">{{__('Edit')}}</span>
                    </a>
        @endif

            {!! setting('homepage_presentation_for_members') !!}
        </div>
        
    </div>
@endif


<div class="flex justify-end">
    @include('partials.preferences-show')
</div>


<div class="flex justify-between my-4 items-center">
    <form up-follow class="form-inline my-2 my-lg-0" role="search" action="{{ url('search') }}"
        method="get">
        <div class="input-group">
            <input
                class="shadow-md appearance-none border rounded-lg py-2 px-3 w-32 sm:w-56 text-sm text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                type="text" name="query" placeholder="{{ trans('messages.search') }}..."
                aria-label="Search">
        </div>
    </form>


    <a class="btn btn-primary" href="{{ route('discussions.create') }}">
        <i class="fas fa-pencil-alt"></i>
        <span class="hidden md:inline ml-2">{{ trans('discussion.create_one_button') }}</span>
    </a>
</div>


@if($discussions->count() > 0)
    <div class="">
        @foreach($discussions as $discussion)
            @include('discussions.discussion')
        @endforeach
    </div>
@endif



@if($actions->count() > 0)
    <div class="my-8">
        @foreach($actions as $action)
            @include('actions.action')
        @endforeach
    </div>
@endif



@endsection