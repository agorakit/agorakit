@extends('app')

@section('content')

    @include('groups.tabs')
    <div class="tab_content">

        <div class="discussion mb-5">

            <div class="d-flex">


                <div class="avatar mr-2 mt-1">
                    <img src="{{route('users.cover', [$discussion->user, 'small'])}}" class="rounded-circle"/>
                </div>

                <div>

                    <div class="d-flex justify-content-between">
                        <h2 class="name">
                            {{ $discussion->name }}
                        </h2>

                        <div class="ml-4 dropdown">
                            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="far fa-edit" aria-hidden="true"></i>

                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                                @can('update', $discussion)
                                    <a class="dropdown-item" href="{{ route('groups.discussions.edit', [$group, $discussion]) }}">
                                        <i class="fa fa-pencil"></i>
                                        {{trans('messages.edit')}}
                                    </a>
                                @endcan

                                @can('delete', $discussion)
                                    <a up-modal=".dialog" class="dropdown-item" href="{{ route('groups.discussions.deleteconfirm', [$group, $discussion]) }}">
                                        <i class="fa fa-trash"></i>
                                        {{trans('messages.delete')}}
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

                        @if ($discussion->tags->count() > 0)
                            <div class="mr-2">
                                @foreach ($discussion->tags as $tag)
                                    @include('tags.tag')
                                @endforeach
                            </div>
                        @endif

                        @can('update', $discussion)
                            <div>
                                <a class="small" up-popup=".dialog" href="{{ route('groups.discussions.tags.edit', [$group, $discussion]) }}">
                                    {{__('Edit tags')}}
                                </a>
                            </div>
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
                @include ('comments.create')
            @endcan
        </div>



    </div>
@endsection
