@extends('app')

@section('content')

    @include('groups.tabs')
    <div class="tab_content">

        @include('partials.invite')




        @can('create-discussion', $group)
            <div class="toolbox">

                <a class="btn btn-primary" href="{{ route('groups.discussions.create', $group->id ) }}">
                    <i class="fa fa-plus"></i> {{trans('discussion.create_one_button')}}
                </a>

            </div>
        @endcan


        <table class="table table-hover special">
            <thead>
                <tr>
                    <th class="avatar"></th>
                    <th class="summary"></th>
                    <th class="date"></th>
                    <th class="unread"></th>
                </tr>
            </thead>

            <tbody>
                @forelse( $discussions as $discussion )
                    <tr>
                        <td class="avatar"><span class="avatar"><img src="{{$discussion->user->avatar()}}" class="img-circle"/></span></td>
                        <td class="content">
                            <a href="{{ route('groups.discussions.show', [$discussion->group_id, $discussion->id]) }}">
                                <span class="name">{{ $discussion->name }}</span>
                                <span class="summary">{{summary($discussion->body) }}</span>
                            </a>
                        </td>

                        <td class="date">
                            {{ $discussion->updated_at->diffForHumans() }}
                        </td>

                        <td class="unread">
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


    </div>




@endsection
