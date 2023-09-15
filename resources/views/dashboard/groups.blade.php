@extends('app')

@section('content')

    <h1><a href="{{ route('index') }}" ><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
        {{ trans('messages.all_groups') }}
    </h1>

    <div class="d-flex justify-content-between mb-3">
        <form class="form-inline" role="search" method="GET" action="{{ route('groups.index') }}" up-autosubmit up-delay="500"
            up-target=".groups" up-reveal="false">
            <div class="input-group">
                <input class="form-control" name="search" type="text" value="{{ Request::get('search') }}"
                    aria-label="Search" placeholder="{{ __('Filter') }}...">

                <button class="btn btn-secondary" type="submit"><span class="fa fa-search"></span></button>

            </div>
        </form>

        @can('create', \App\Group::class)
            <div>
                <a class="btn btn-primary" href="{{ route('groups.create') }}" up-target="body">
                    {{ trans('group.create_a_group_button') }}
                </a>
            </div>
        @endcan

    </div>

    <div class="groups">
        @if ($groups)
            {!! $groups->links() !!}
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

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
