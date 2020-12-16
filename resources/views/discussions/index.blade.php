@extends('app')

@section('content')

    @include('groups.tabs')

    @auth
        <div class="md:flex justify-start">
            <div class="flex mb-2 mr-4">
                @include('partials.tags_filter')
            </div>


            
            <form role="search" method="GET" action="./discussions" class="mb-2 mr-4"
                up-autosubmit up-delay="500" up-target=".groups" up-reveal="false">
                
                    <input value="{{ Request::get('search') }}" class="form-control" type="text"
                        name="search" placeholder="{{ __('messages.search') }}..." aria-label="Search">
            </form>
            

            <div class="mr-4">
                @can('create-discussion', $group)
                 <a up-follow class="btn btn-primary"
                    href="{{ route('groups.discussions.create', $group ) }}">
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
