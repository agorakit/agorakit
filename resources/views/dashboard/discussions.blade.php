@extends('app')

@section('content')
    <div class="page_header">
        <h1><a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.discussions') }}</h1>
    </div>


    @include('dashboard.tabs')


    <div class="tab_content">
        @if ($discussions)
            <table class="table table-hover special">
                <thead>
                    <tr>
                        <th class="avatar"></th>
                        <th class="summary"></th>
                        <th style="width:100px" class="date hidden-xs"></th>
                        <th class="unread"></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse( $discussions as $discussion )


                        <tr onclick="document.location = '{{ route('groups.discussions.show', [$discussion->group_id, $discussion->id]) }}';">
                            <td class="avatar"><span class="avatar"><img src="{{$discussion->user->avatar()}}" class="img-circle"/></span></td>
                            <td class="content">
                                <a href="{{ route('groups.discussions.show', [$discussion->group_id, $discussion->id]) }}">
                                    <span class="name">{{ $discussion->name }}</span>
                                    <span class="summary">{{summary($discussion->body) }}</span>
                                    <br/>
                                </a>
                                <span class="group-name"><a href="{{ route('groups.show', [$discussion->group_id]) }}">{{ $discussion->group->name }}</a></span>
                            </td>

                            <td class="date hidden-xs">
                                {{ $discussion->updated_at->diffForHumans() }}
                            </td>

                            <td>
                                @if ($discussion->unReadCount() > 0)
                                    <i class="fa fa-comment"></i>
                                    <span class="badge">{{ $discussion->unReadCount() }}</span>
                                @endif
                            </td>

                        </tr>

                    @empty
                        {{trans('messages.nothing_yet')}}
                    @endforelse
                </tbody>
            </table>


            {!! $discussions->render() !!}
        @else
            {{trans('messages.nothing_yet')}}
        @endif
    </div>

@endsection
