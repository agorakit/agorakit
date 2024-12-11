@extends('app')

@section('content')

    @include('dashboard.tabs')

    <div class="toolbar">
        <form action="{{ route('groups.index') }}" class="form-inline" method="GET" role="search" up-autosubmit up-scroll="false"
            up-target=".groups" up-watch-delay="500">
            <div class="input-group">
                <input aria-label="Search" class="form-control" name="search" placeholder="{{ __('Filter') }}..." type="text"
                    value="{{ Request::get('search') }}">

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
            @foreach ($groups as $group)
                @include('groups.group')
            @endforeach
            {!! $groups->links() !!}
        @else
            <div class="alert alert-info" role="alert">
                {{ trans('There are no groups yet, create one') }}
            </div>
        @endif
    </div>

@endsection
