@extends('app')

@section('content')

@include('groups.tabs')
<div class="tab_content">

    <div class="discussion mb-5">

        <div class="flex">



            <img src="{{ route('users.cover', [$discussion->user, 'small']) }}"
                class="rounded-full h-12 w-12 flex-shrink-0 mr-4" />


            <div class="flex-grow">

                <div class="flex justify-content-between">
                    <h2 class="flex-grow text-xxl">
                        {{ $discussion->name }}
                    </h2>

                    <div class="ml-auto">
                        <div class="d-flex align-items-start">
                            @if($discussion->isPinned())
                                <div class="bg-red-900 text-red-100 text-xs rounded-full px-3 py-1">
                                    {{ __('Pinned') }}</div>
                            @endif
                            @if($discussion->isArchived())
                                <div class="bg-red-900 text-red-100 text-xs rounded-full px-3 py-1">
                                    {{ __('Archived') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="ml-4 dropdown">
                        <a class="text-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                            @can('update', $discussion)
                                <a class="dropdown-item"
                                    href="{{ route('groups.discussions.edit', [$group, $discussion]) }}">
                                    <i class="fa fa-pencil"></i>
                                    {{ trans('messages.edit') }}
                                </a>
                            @endcan

                            @can('delete', $discussion)
                                <a up-modal=".dialog" class="dropdown-item"
                                    href="{{ route('groups.discussions.deleteconfirm', [$group, $discussion]) }}">
                                    <i class="fa fa-trash"></i>
                                    {{ trans('messages.delete') }}
                                </a>
                            @endcan

                            @can('pin', $discussion)
                                <a class="dropdown-item"
                                    href="{{ route('groups.discussions.pin', [$group, $discussion]) }}">
                                    <i class="fa fa-thumbtack"></i>
                                    @if($discussion->isPinned())
                                        {{ trans('messages.unpin') }}
                                    @else
                                        {{ trans('messages.pin') }}
                                    @endif
                                </a>
                            @endcan

                            @can('archive', $discussion)
                                <a class="dropdown-item"
                                    href="{{ route('groups.discussions.archive', [$group, $discussion]) }}">
                                    <i class="fa fa-archive"></i>
                                    @if($discussion->isArchived())
                                        {{ trans('messages.unarchive') }}
                                    @else
                                        {{ trans('messages.archive') }}
                                    @endif
                                </a>
                            @endcan

                            @if($discussion->revisionHistory->count() > 0)
                                <a class="dropdown-item"
                                    href="{{ route('groups.discussions.history', [$group, $discussion]) }}"><i
                                        class="fa fa-history"></i>
                                    {{ trans('messages.show_history') }}</a>
                            @endif
                        </div>
                    </div>

                </div>





                <div class="meta">
                    {{ trans('messages.started_by') }}
                    <span class="user">
                        <a up-follow
                            href="{{ route('users.show', [$discussion->user]) }}">{{ $discussion->user->name }}</a>
                    </span>
                    {{ trans('messages.in') }} {{ $discussion->group->name }}
                    {{ $discussion->created_at->diffForHumans() }}
                </div>

                <div class="mb-3 d-flex">

                    <div class="tags">
                        @if($discussion->getSelectedTags()->count() > 0)
                            <span class="mr-2">
                                @foreach($discussion->getSelectedTags() as $tag)
                                    @include('tags.tag')
                                @endforeach
                            </span>
                        @endif
                    </div>


                </div>

                

            </div>

        </div>

         <div class="sm:ml-16 lg:mr-40">
            {!! filter($discussion->body) !!}
        </div>

    </div>




    <div class="comments">
        @foreach($discussion->comments as $comment_key => $comment)

        @if ($comment_key == $read_count)
        <div class="w-full flex justify-center my-4" id="unread">
            <div class="inline-block bg-red-700 text-red-100 rounded-full px-4 py-2 text-sm uppercase">
                <i class="far fa-arrow-alt-circle-down mr-2"></i> {{trans('messages.new')}}
            </div>
        </div>
        @endif
            @include('comments.comment')
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
            <div class="sm:ml-16  lg:mr-40">
                <h2>@lang('messages.reply')</h2>
                @include('comments.create')
                </div>
            @endif
        @endcan

    </div>



</div>
@endsection