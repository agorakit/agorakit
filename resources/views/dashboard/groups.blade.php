@extends('app')

@section('content')

    <div class="mb-2">
        @include('dashboard.tabs')
    </div>

    <div class="d-flex justify-content-between mb-4 gap-2 flex-wrap">
        <form class="form-inline" role="search" method="GET" action="{{ route('groups.index') }}" up-autosubmit up-delay="500"
            up-target=".groups" up-scroll="false">
            <div class="input-group">
                <input class="form-control" name="search" type="text" value="{{ Request::get('search') }}"
                    aria-label="Search" placeholder="{{ __('Filter') }}...">

                <button class="btn btn-secondary" type="submit"><span class="fa fa-search"></span></button>

            </div>
        </form>

        @can('create', \App\Group::class)
            <div>
                <a class="btn btn-primary" href="{{ route('groups.create') }}">
                    {{ trans('group.create_a_group_button') }}
                </a>
            </div>
        @endcan

    </div>

    <div class="groups">
        @if ($groups)
            {!! $groups->links() !!}
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
                @foreach ($groups as $group)
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
