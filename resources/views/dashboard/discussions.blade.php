@extends('app')

@section('content')
    <div class="d-flex flex-wrap gap-2 justify-content-between">
        <div>
            <a class="btn btn-primary" href="{{ route('discussions.create') }}">
                {{ trans('messages.create_discussion') }}
            </a>
        </div>

    </div>
    

    <div class="mt-4" up-poll>
        @forelse($discussions as $discussion)
            @include('discussions.discussion')
        @empty
            {{ trans('messages.nothing_yet') }}
        @endforelse
        {!! $discussions->render() !!}
    </div>

    <div class="mt-16 text-secondary">
        <a class="btn btn-secondary" href="{{ route('discussions.feed') }}"><i class="fas fa-rss"></i> RSS</a>
    </div>
@endsection
