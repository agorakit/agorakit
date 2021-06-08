@extends('app')

@section('content')


@include('users.tabs')

<div class="tab_content mt-10 mb-32">

    @if(Auth::user())

        <div class="sm:flex">

            
            <img src="{{ route('users.cover', [$user, 'medium']) }}"
                class="rounded-full mx-auto h-48 w-48 flex-shrink-0 sm:mx-10 block" />
            

            <div class="">

                <div class="text-xl my-1 font-bold">
                    {{ $user->name }}
                </div>

                <div class="text-gray-600 my-1">
                    {{ '@' .$user->username }}
                </div>


                @if($user->tags->count() > 0)
                    <div class="my-1">
                        @foreach($user->tags as $tag)
                            @include('tags.tag')
                        @endforeach
                    </div>
                @endif


                <div class="text-sm my-1">
                    {{ trans('messages.registered') }} : {{ dateForHumans($user->created_at) }}
                </div>



                <div class="my-1">
                    {!! filter($user->body) !!}
                </div>


                <div class="">
                    @foreach($user->groups as $group)
                        @unless($group->isSecret())
                            <a up-follow href="{{ route('groups.show', [$group]) }}"
                                class="inline-block bg-gray-300 text-gray-700 rounded-full text-xs px-2 py-1 mr-1 mb-1 no-underline">

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

            </div>





        </div>

    @endif

</div>
@endsection