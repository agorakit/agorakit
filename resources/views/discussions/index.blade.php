@extends('app')

@section('content')

@include('groups.tabs')

@auth
<div class="sm:flex sm:justify-between sm:space-x-4 space-y-4 sm:space-y-0 mb-6">
    {{--@include('partials.tags_filter')--}}
    <div>
        <form role="search" method="GET" action="./discussions" up-autosubmit up-delay="500" up-target=".groups"
            up-reveal="false">

            <input value="{{ Request::get('search') }}" class="form-control" type="text" name="search"
                placeholder="{{ __('messages.search') }}..." aria-label="Search">
        </form>
    </div>


    <div class="">
        @can('create-discussion', $group)
        <a up-follow class="btn btn-primary" href="{{ route('groups.discussions.create', $group ) }}">
            {{ trans('discussion.create_one_button') }}
        </a>
        @endcan
    </div>
</div>
@endauth


<div class="discussions items">
    @forelse( $discussions as $discussion )
    @include('discussions.discussion')
    @empty
    {{trans('messages.nothing_yet')}}
    @endforelse

    {!! $discussions->render() !!}
</div>



@endsection