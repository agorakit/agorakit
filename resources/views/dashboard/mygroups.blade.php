@extends('app')

@section('content')

    <div class="d-flex justify-content-between mb-3">

        <h1><a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.my_groups') }}</h1>


        <form class="form-inline" role="search" method="GET" action="{{route('groups.index.my')}}" up-autosubmit up-delay="100" up-target=".groups" up-reveal='false'>
            <div class="input-group">
                <input value="{{Request::get('search')}}" class="form-control" type="text" name="search"  placeholder="{{__('Filter')}}..." aria-label="Search">

                <div class="input-group-append">
                    <button class="btn btn-secondary" type="submit"><span class="fa fa-search"></span></button>
                </div>
            </div>
        </form>

    </div>



    <div class="groups">
        @if ($groups)
            {!! $groups->appends(request()->query())->links() !!}

            <div class="row mb-3">
                @forelse( $groups as $group )
                    @include('groups.group')



                    @if ($loop->iteration % 3 == 0)
                    </div>
                    <div class="row mb-3">
                    @endif



                @empty
                    <div class="alert alert-info" role="alert">
                        {{trans('messages.nothing_yet')}}
                    </div>
                @endforelse


            </div>

            {!! $groups->appends(request()->query())->links() !!}

        @else
            <div class="alert alert-info" role="alert">
                {{trans('messages.nothing_yet')}}
            </div>
        @endif
    </div>


@endsection
