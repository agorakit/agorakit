@extends('app')

@section('content')

    @include('groups.tabs')

    @auth
        <div class="flex justify-between">
            <div class="flex mb-2">
                @include('partials.tags_filter')
            </div>

            <div class="">
                @can('create-discussion', $group)
                 <a up-follow class="bg-gray-700 text-gray-100 rounded-full shadow-md text-sm h-10 px-4 flex items-center"
            href="{{ route('groups.discussions.create', $group ) }}">
            <i class="fas fa-pencil-alt"></i>
            <span class="hidden md:inline ml-2">{{ trans('discussion.create_one_button') }}</span>
        </a>
                    
                @endcan
            </div>
        </div>
    @endauth


    <div class="discussions items">
        @forelse( $discussions as $discussion )
            @include('discussions.discussion')
        @empty
            {{trans('messages.nothing_yet')}}
        @endforelse

        {!! $discussions->render() !!}
    </div>



@endsection
