@extends('group')

@section('content')
    @auth
        <div class="d-flex justify-content-between">
            
            <div>
                <form role="search" method="GET" action="./discussions" up-autosubmit up-delay="500" up-target=".groups" up-reveal="false">

                    <input class="form-control" name="search" type="text" value="{{ Request::get('search') }}" aria-label="Search" placeholder="{{ __('messages.search') }}...">
                </form>
            </div>

            <div class="">
                @can('create-discussion', $group)
                    <a class="btn btn-primary" href="{{ route('groups.discussions.create', $group) }}" up-follow>
                        {{ trans('discussion.create_one_button') }}
                    </a>
                @endcan
            </div>
        </div>
    @endauth

    <div class="discussions items">
        @forelse($discussions as $discussion)
            @include('discussions.discussion')
        @empty
            {{ trans('messages.nothing_yet') }}
        @endforelse

        {!! $discussions->render() !!}
    </div>
@endsection
