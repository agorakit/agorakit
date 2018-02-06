@extends('app')

@section('content')

    @include('groups.tabs')
    <div class="tab_content">

        @include('partials.invite')


        @can('create-discussion', $group)
            <div class="toolbox">
                <a class="btn btn-primary" href="{{ route('groups.discussions.create', $group->id ) }}">
                    <i class="fa fa-plus"></i> {{trans('discussion.create_one_button')}}
                </a>
            </div>
        @endcan

        <div class="discussions">
            @forelse( $discussions as $discussion )
                @include('discussions.discussion')
            @empty
                {{trans('messages.nothing_yet')}}
            @endforelse

            {!! $discussions->render() !!}
        </div>

    </div>

@endsection
