@extends('app')

@section('content')
    <div class="d-flex justify-content-between mb-4 gap-2 flex-wrap">
        <form action="{{ route('groups.index') }}" class="form-inline" method="GET" role="search" up-autosubmit
            up-scroll="false" up-target=".groups" up-watch-delay="500">
            <div class="input-group">
                <input aria-label="Search" class="form-control" name="search" placeholder="{{ __('Filter') }}..."
                    type="text" value="{{ Request::get('search') }}">

                <button class="btn btn-secondary" type="submit"><span class="fa fa-search"></span></button>

            </div>
        </form>

        @can('create', \App\Group::class)
            <div>
                <a class="btn btn-primary" href="{{ route('groups.create') }}">
                    {{ trans('group.create_a_group_button') }}
                </a>
            </div>
            {!! Form::open(array('action' => 'GroupController@import', 'files'=>true, 'up-autosubmit')) !!}
            <!-- form action="{{ route('groups.import') }}" class="form-inline" method="POST" up-autosubmit -->
            <div class="form-group"><a class="btn btn-primary">
                {!! Form::label('import', trans('group.import_a_group')) !!}
                {!! Form::file('import', null, ['accept' => '.zip, .json', 'class' => 'form-control']) !!}
                {!! Form::close() !!}
            </a></div>
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
