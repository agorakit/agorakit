@extends('app')

@section('content')

@include('groups.tabs')
<div class="tab_content">

    <div class="discussion mb-5">


        @if ($total_count == 0)
        {{-- no comments yet, we scroll right here --}}
        <div id="unread">

        </div>
        @endif

        <div class="flex">
            @include('users.avatar', ['user' => $discussion->user])
            <div class="flex-grow w-100 ml-4">
                <h2 class="text-xxl">
                    {{ $discussion->name }}
                </h2>
            </div>
            @include('discussions.dropdown')
        </div>

        <div class="ml-16">
            <div>
                {!! filter($discussion->body) !!}
            </div>

            <div class="text-xs text-gray-500">
                {{ trans('messages.started_by') }}
                <span class="user">
                    <a up-follow href="{{ route('users.show', [$discussion->user]) }}">{{ $discussion->user->name }}</a>
                </span>
                {{ trans('messages.in') }} {{ $discussion->group->name }}
                {{ dateForHumans($discussion->created_at) }}
            </div>

            <div class="mb-3 d-flex">
                <div class="tags">
                    @if($discussion->getSelectedTags()->count() > 0)
                    <span class="me-2">
                        @foreach($discussion->getSelectedTags() as $tag)
                        @include('tags.tag')
                        @endforeach
                    </span>
                    @endif
                </div>
            </div>

            <div>
                <x-reactions :model="$discussion"/>
            </div>
    

        </div>

    </div>

    {{--
    {{$read_count}} / {{$total_count}}
    --}}



    <div class="comments">
        @foreach($discussion->comments as $comment_key => $comment)


        {{-- {{$comment_key}} / {{$read_count}} / {{$total_count}} --}}


        {{-- this is the first new unread comment --}}
        @if ($comment_key == $read_count)
        <div class="w-full d-flex justify-center my-4" id="unread">
            <div class="inline-block bg-red-700 text-red-100 rounded-full px-4 py-2 text-sm uppercase">
                <i class="far fa-arrow-alt-circle-down me-2"></i> {{trans('messages.new')}}
            </div>
        </div>
        @endif



        @include('comments.comment')

        {{-- this is the latest comment, it is read, we scroll below after the comment post box --}}
        @if ($comment_key + 1 == $read_count)
        <div class="w-full d-flex justify-center my-4" id="last_read">
            <div class="inline-block bg-gray-500 text-gray-100 rounded-full px-4 py-2 text-sm uppercase">
                <i class="far fa-arrow-alt-circle-up me-2"></i> {{trans('messages.all_is_read')}}
            </div>
        </div>
        @endif

        @endforeach

        @auth
        @if(isset($comment))
        <div class="poll" id="live"
            up-data='{"url": "{{ route('groups.discussions.live', [$group, $discussion, $comment]) }}"}'>
            <div id="live-content"></div>
        </div>
        @endif

        @endauth


        @can('create-comment', $group)
        @if($discussion->isArchived())
        <div class="alert alert-info">
            @lang('This discussion is archived, you cannot comment anymore')
        </div>
        @else
        {{--<h2>@lang('messages.reply')</h2>--}}
        <div class="flex">
            <img src="{{route('users.cover', [Auth::user(), 'small'])}}"
                class="rounded-full h-10 w-10 sm:h-12 sm:w-12 flex-shrink-0 me-2 sm:mr-4" />
            <div class="flex-grow">
                @include('comments.create')
            </div>
        </div>
        @endif
        @endcan


    </div>



</div>
@endsection