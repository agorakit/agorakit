@extends('app')

@section('content')


@include('users.tabs')

<div class="tab_content">

    @if(Auth::user())

        <div class="md:flex text-center md:text-left max-w-4xl">

            <img src="{{ route('users.cover', [$user, 'medium']) }}"
                class="rounded-full h-32 w-32 md:h-48 md:w-48 mx-auto block my-4 md:mr-8 flex-shrink-0" />


            <div class="">

                <div class="text-4xl my-1">
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
                    {{ trans('messages.registered') }} : {{ $user->created_at->diffForHumans() }}
                </div>



                <div class="my-1">
                    {!! filter($user->body) !!}
                </div>


                <div class="">
                    @foreach($user->groups as $group)
                        @unless($group->isSecret())
                            <a up-follow href="{{ route('groups.show', [$group]) }}"
                                class="inline-block bg-gray-300 text-gray-700 rounded-full text-xs px-2 py-1 mr-1 mb-1">

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