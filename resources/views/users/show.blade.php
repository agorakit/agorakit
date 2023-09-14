@extends('app')

@section('content')


    @include('users.tabs')



    @if (Auth::user())

        <div class="row">

            <div class="col">

                <h2>
                    {{ $user->name }}
                </h2>

                <div class="text-secondary my-1">
                    {{ '@' . $user->username }}
                </div>


                @if ($user->tags->count() > 0)
                    <div class="my-1">
                        @foreach ($user->tags as $tag)
                            @include('tags.tag')
                        @endforeach
                    </div>
                @endif


                <div>
                    {{ trans('messages.registered') }} : {{ dateForHumans($user->created_at) }}
                </div>



                <div class="my-3">
                    {!! filter($user->body) !!}
                </div>


                <div>
                    @foreach ($user->groups as $group)
                        @unless ($group->isSecret())
                            <a up-follow href="{{ route('groups.show', [$group]) }}"
                                class="inline-block bg-gray-300 text-gray-700 rounded-full text-xs px-2 py-1 mr-1 mb-1 no-underline">

                                @if ($group->isOpen())
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

            <div class="col">
                <img src="{{ route('users.cover', [$user, 'medium']) }}" class="rounded" />
            </div>


        </div>

    @endif

    </div>
@endsection
