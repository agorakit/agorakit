@extends('app')

@section('content')


<div class="d-flex mb-2">
    <h1><a up-follow href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i
            class="fa fa-angle-right"></i> {{ trans('messages.discussions') }}</h1>
</div>


<div class="flex justify-between">
    <div>
        @include('partials.preferences-show')
    </div>


    <div>
        <a up-follow class="btn btn-primary" href="{{ route('discussions.create') }}">
            <i class="fas fa-pencil-alt"></i>
            <span class="hidden md:inline ml-2">
                {{ trans('discussion.create_one_button') }}
            </span>
        </a>
    </div>

</div>

<div class="discussions">
    @forelse( $discussions as $discussion )
        @include('discussions.discussion')
    @empty
        {{ trans('messages.nothing_yet') }}
    @endforelse
    {!! $discussions->render() !!}
</div>

<div class="mt-16 text-secondary">
    <a class="btn btn-secondary" href="{{route('discussions.feed')}}"><i class="fas fa-rss"></i> RSS</a>
</div>

@endsection