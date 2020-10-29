@extends('app')

@section('content')

<div class="d-md-flex justify-content-between mb-3">

    <h1><a up-follow href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i
            class="fa fa-angle-right"></i>
        {{ trans('messages.all_groups') }}
    </h1>


    <form class="form-inline" role="search" method="GET" action="{{ route('groups.index') }}"
        up-autosubmit up-delay="500" up-target=".groups" up-reveal="false">
        <div class="input-group">
            <input value="{{ Request::get('search') }}" class="form-control" type="text"
                name="search" placeholder="{{ __('Filter') }}..." aria-label="Search">

            <div class="input-group-append">
                <button class="btn btn-secondary" type="submit"><span class="fa fa-search"></span></button>
            </div>
        </div>
    </form>
</div>



<div class="groups">
    @if($groups)
        {!! $groups->links() !!}
        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 py-4">
            @foreach($groups as $group)
                @include('groups.group')
            @endforeach
        </div>
        {!! $groups->links() !!}
    @else
        <div class="alert alert-info" role="alert">
            {{ trans('messages.nothing_yet') }}
        </div>
    @endif
</div>









@endsection