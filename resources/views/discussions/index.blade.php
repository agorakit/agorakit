@extends('app')

@section('content')

    @include('groups.tabs')

    @auth
        <div class="toolbox d-md-flex">
            <div class="d-flex mb-2">
                @include('partials.tags_filter')
            </div>

            <div class="ml-auto">
                @can('create-discussion', $group)
                    <div class="toolbox">
                        <a class="btn btn-primary" href="{{ route('groups.discussions.create', $group ) }}">
                            <i class="fa fa-plus"></i> {{trans('discussion.create_one_button')}}
                        </a>
                    </div>
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
