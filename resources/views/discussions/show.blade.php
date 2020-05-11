@extends('app')

@section('content')

    @include('groups.tabs')
    <div class="tab_content">

        <div class="discussion mb-5">

            <div class="d-flex">


                <div class="avatar mr-2">
                    <img src="{{route('users.cover', [$discussion->user, 'small'])}}" class="rounded-circle"/>
                </div>

                <div style="width: 100%;">

                    <div class="d-flex justify-content-between">
                        <h2 class="name">
                            {{ $discussion->name }}
                        </h2>

                        <div class="ml-auto">
                            <div class="d-flex align-items-start">
                                @if ($discussion->isPinned())
                                    <div class="badge badge-primary" style="min-width: 2em; margin: 0 2px;">{{__('Pinned')}}</div>
                                @endif
                                @if ($discussion->isArchived())
                                    <div class="badge badge-muted" style="min-width: 2em; margin: 0 2px;">{{__('Archived')}}</div>
                                @endif
                            </div>
                        </div>

                        <div class="ml-4 dropdown">
                            <a class="text-secondary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                                @can('update', $discussion)
                                    <a class="dropdown-item" href="{{ route('groups.discussions.edit', [$group, $discussion]) }}">
                                        <i class="fa fa-pencil"></i>
                                        {{trans('messages.edit')}}
                                    </a>
                                @endcan

                                @can('update', $discussion)
                                    <a class="dropdown-item" up-modal=".dialog" up-closable="false" href="{{ route('tagger.index', ['discussions', $discussion->id]) }}?r={{rand(0,999999)}}">
                                        <i class="fa fa-tag"></i>
                                        {{__('Edit tags')}}
                                    </a>
                                @endcan

                                @can('delete', $discussion)
                                    <a up-modal=".dialog" class="dropdown-item" href="{{ route('groups.discussions.deleteconfirm', [$group, $discussion]) }}">
                                        <i class="fa fa-trash"></i>
                                        {{trans('messages.delete')}}
                                    </a>
                                @endcan

                                @can('pin', $discussion)
                                    <a class="dropdown-item" href="{{ route('groups.discussions.pin', [$group, $discussion]) }}">
                                        <i class="fa fa-thumbtack"></i>
                                        @if($discussion->isPinned())
                                            {{trans('messages.unpin')}}
                                        @else
                                            {{trans('messages.pin')}}
                                        @endif
                                    </a>
                                @endcan

                                @can('archive', $discussion)
                                    <a class="dropdown-item" href="{{ route('groups.discussions.archive', [$group, $discussion]) }}">
                                        <i class="fa fa-archive"></i>
                                        @if($discussion->isArchived())
                                            {{trans('messages.unarchive')}}
                                        @else
                                            {{trans('messages.archive')}}
                                        @endif
                                    </a>
                                @endcan

                                @if ($discussion->revisionHistory->count() > 0)
                                    <a class="dropdown-item" href="{{route('groups.discussions.history', [$group, $discussion])}}"><i class="fa fa-history"></i> {{trans('messages.show_history')}}</a>
                                @endif
                            </div>
                        </div>

                    </div>





                    <div class="meta">
                        {{trans('messages.started_by')}}
                        <span class="user">
                            <a up-follow href="{{ route('users.show', [$discussion->user]) }}">{{ $discussion->user->name}}</a>
                        </span>
                        {{trans('messages.in')}} {{ $discussion->group->name}} {{ $discussion->created_at->diffForHumans()}}
                    </div>

                    <div class="mb-3 d-flex">

                      <div class="tags">
                       @if ($discussion->tags->count() > 0)
                        <span class="mr-2">
                          @foreach ($discussion->tags as $tag)
                            @include('tags.tag')
                          @endforeach
                        </span>
                       @endif
                      </div>

                    @can('update', $discussion)
                      <a class="small" up-modal=".dialog" up-closable="false" href="{{ route('tagger.index', ['discussions', $discussion->id]) }}?r={{rand(0,999999)}}">
                        {{__('Edit tags')}}
                      </a>
                    @endcan




                    </div>

                    <div class="body">
                        {!! filter($discussion->body) !!}
                    </div>

                </div>

            </div>

        </div>




        <div class="comments" >

            @foreach ($discussion->comments as $comment_key => $comment)
                @include('comments.comment')
            @endforeach

            @auth
                @if (isset($comment))
                    <div class="poll" id="live" up-data='{"url": "{{route('groups.discussions.live', [$group, $discussion, $comment])}}"}'>
                        <div id="live-content"></div>
                    </div>
                @endif

            @endauth


            @can('create-comment', $group)
                @if ($discussion->isArchived())
                    <div class="alert alert-info">
                        @lang('This discussion is archived, you cannot comment anymore')
                    </div>
                @else
                    @include ('comments.create')
                @endif
            @endcan

        </div>



    </div>
@endsection
