@extends('app')

@section('content')

    @include('partials.grouptab')
    <div class="tab_content">

        @include('partials.invite')

        <h2>{{trans('discussion.all_in_this_group')}}

            @can('create-discussion', $group)
                <a class="btn btn-primary btn-xs" href="{{ action('DiscussionController@create', $group->id ) }}">
                    <i class="fa fa-plus"></i> {{trans('discussion.create_one_button')}}
                </a>
            @endcan
        </h2>

        <table class="table table-hover special">
            <thead>
                <tr>
                    <th class="avatar"></th>
                    <th class="summary">{{trans('messages.title')}}</th>
                    <th class="date">{{trans('messages.date')}}</th>
                    <th class="unread">{{trans('messages.to_read')}}</th>
                </tr>
            </thead>

            <tbody>
                @forelse( $discussions as $discussion )
                    <tr>
                        <td class="avatar"><span class="avatar"><img src="{{$discussion->user->avatar()}}" class="img-circle"/></span></td>
                        <td class="content">
                            <a href="{{ action('DiscussionController@show', [$discussion->group_id, $discussion->id]) }}">
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
