@extends('app')

@section('content')
    <div class="page_header">
        <h1><a href="{{ action('DashboardController@index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.unread_discussions') }}</h1>
    </div>
    <div class="tab_content">
        @if ($discussions)
            <table class="table table-hover special">
                <thead>
                    <tr>
                        <th style="width: 65%">{{trans('messages.title')}}</th>
                        <th style="width: 15%">{{trans('messages.group')}}</th>
                        <th>{{trans('messages.date')}}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $discussions as $discussion )
                        <tr>
                            <td class="content">
                                <a href="{{ action('DiscussionController@show', [$discussion->group_id, $discussion->id]) }}">
                                    <span class="name">{{ $discussion->name }}</span>
                                    <span class="summary">{{ summary($discussion->body) }}</span>
                                </a>
                            </td>

                            <td>
                                <span class="label label-default"><a href="{{ action('DiscussionController@show', [$discussion->group, $discussion]) }}">{{$discussion->group->name}}</a></span>
                            </td>

                            <td>
                                {{ $discussion->updated_at->diffForHumans() }}
                            </td>

                            <td>
                                @if ($discussion->unReadCount() > 0)
                                    <i class="fa fa-comment"></i>
                                    <span class="badge">{{ $discussion->unReadCount() }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $discussions->render() !!}
        @else
            {{trans('messages.nothing_yet')}}
        @endif
    </div>

@endsection
