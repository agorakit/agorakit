@extends('app')

@section('content')

    <div class="d-flex flex-wrap gap-2 justify-content-between">
        <h1><i class="fa fa-search"></i> {{ trans('messages.your_search_for') }} <strong>"{{ $query ?? '' }}"</strong></h1>
    </div>

    <form action="{{ url('search') }}" method="get" role="search">

        <div class="form-group">
            <input aria-label="Search" class="form-control" name="query" placeholder="{{ trans('messages.search') }}"
                type="search" value="{{ request()->get('query') }}" />
        </div>

        <div class="form-group">
            <select class="form-control" name="type" required="required">
                <option @if ($type == 'discussions') selected @endif value="discussions">
                    {{ trans('messages.discussions') }}</option>
                <option @if ($type == 'comments') selected @endif value="comments">{{ trans('messages.comments') }}
                </option>
                <option @if ($type == 'calendarevents') selected @endif value="calendarevents">{{ trans('messages.events') }}
                </option>
                <option @if ($type == 'users') selected @endif value="users">{{ trans('messages.users') }}
                </option>
                <option @if ($type == 'files') selected @endif value="files">{{ trans('messages.files') }}
                </option>
                <option @if ($type == 'groups') selected @endif value="groups">{{ trans('messages.groups') }}
                </option>

            </select>
        </div>

        <div class="form-group">
            <select class="form-control" name="scope">
                <option @if ($scope == 'joined') selected @endif value="my"> MY groups</option>
                <option @if ($scope == 'all') selected @endif value="all"> All public groups</option>
                @if (Auth::user()->isAdmin())
                    <option @if ($scope == 'admin') selected @endif value="admin"> All groups (admin overview)
                    </option>
                @endif

                @foreach (Auth::user()->groups as $group)
                    <option @if ($scope == $group->id) selected @endif value="{{ $group->id }}">
                        {{ $group->name }}</option>
                @endforeach
            </select>
        </div>



        <div class="form-group">
            <button class="form-control btn btn-primary" type="submit">{{ trans('messages.search') }}</button>
        </div>

    </form>


    @if ($type == 'groups')
        @if ($results->count() > 0)
            @foreach ($results as $group)
                @include('groups.group-list')
            @endforeach
            {!! $results->render() !!}
        @endif
    @endif

    @if ($type == 'discussions')
        @if ($results->count() > 0)
            @foreach ($results as $discussion)
                @include('discussions.discussion')
            @endforeach
            {!! $results->render() !!}
        @endif
    @endif

    @if ($type == 'calendarevents')
        @if ($results->count() > 0)
            @include('calendarevents.list', ['events' => $results])
        @endif
    @endif

    @if ($type == 'users')
        @if ($results->count() > 0)
            @foreach ($results as $user)
                @include('users.user-list')
            @endforeach
            {!! $results->render() !!}
        @endif
    @endif

    @if ($type == 'comments')
        @if ($results->count() > 0)
            @foreach ($results as $comment)
                @include('comments.comment')
            @endforeach
            {!! $results->render() !!}
        @endif
    @endif

    @if ($type == 'files')
        @if ($results->count() > 0)
            @foreach ($results as $file)
                @include('files.file')
            @endforeach
            {!! $results->render() !!}
        @endif
    @endif

    @if ($results->count() == 0)
        Nothing found
    @endif

@endsection
