@extends('app')

@section('content')
    <h1><a  href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
        {{ trans('messages.discussions') }}</h1>



    <div class="d-flex justify-content-between">
        <div>
            @include('partials.preferences-show')
        </div>


        <div>
            <a  class="btn btn-primary" href="{{ route('discussions.create') }}">
                <i class="fas fa-pencil-alt me-2"></i>

                {{ trans('discussion.create_one_button') }}

            </a>
        </div>

    </div>

    <div class="mt-4">
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
