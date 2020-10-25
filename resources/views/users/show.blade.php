@extends('app')

@section('content')


@include('users.tabs')

<div class="tab_content">

    @if(Auth::user())

        <div class="md:flex text-center md:text-left">

            <img src="{{ route('users.cover', [$user, 'medium']) }}"
                class="rounded-full h-32 w-32 mx-auto block my-4 md:mr-4" />


            <div class="">

            <div class="text-4xl my-1">
            {{ $user->name }}
            </div>

            <div class="text-gray-600 my-1">
            {{ '@' .$user->username }}
            </div>

                <div class="meta mb-4">
                    {{ trans('messages.registered') }} : {{ $user->created_at->diffForHumans() }}
                </div>

                <div>
                    {!! filter($user->body) !!}
                </div>

                
                <div class="text-left mb-3 sm:grid-cols-2 lg:grid-cols-3 grid">
                    @foreach($user->groups as $group)
                        @unless($group->isSecret())
                            <a up-follow href="{{ route('groups.show', [$group]) }}"
                                class=" text-gray-600 text-sm">

                                @if($group->isOpen())
                                    <i class="fa fa-globe" title="{{ trans('group.open') }}"></i>
                                @elseif($group->isClosed())
                                    <i class="fa fa-lock" title="{{ trans('group.closed') }}"></i>
                                @endif
                                {{ $group->name }}

                            </a>
                        @endunless
                    @endforeach
                    </div>
                


                <div>
                    @if($user->tags->count() > 0)
                        @foreach($user->tags as $tag)
                            @include('tags.tag')
                        @endforeach
                    @endif
                </div>




            </div>





        </div>

    @endif

</div>
@endsection