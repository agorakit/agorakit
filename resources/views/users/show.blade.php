@extends('app')

@section('content')
    @auth
        <div class="row">
            <div class="col-12 col-md-6 mb-2 order-md-2">
                @include ('users.cover', ['user => $user'])
            </div>

            <div class="col-12 col-md-6">
                <h2 class="mb-1">
                    {{ $user->name }}
                </h2>

                <div class="text-secondary my-1">
                    {{ '@' . $user->username }}
                </div>

                @if ($user->tags->count() > 0)
                    <div class="d-flex flex-wrap gap-1 mb-1">
                        @foreach ($user->tags as $tag)
                            @include('tags.tag')
                        @endforeach
                    </div>
                @endif

                <div class="text-meta mb-2">
                    <div>
                        {{ t('Registered') }} : {{ dateForHumans($user->created_at) }}
                    </div>

                    <div>
                        {{ t('Last activity') }} : {{ $user->updated_at->diffForHumans() }}
                    </div>
                </div>

                <div class="mb-3">
                    {!! filter($user->body) !!}
                </div>

                <div class="d-flex flex-wrap gap-1 mb-2">
                    @foreach ($user->groups as $group)
                        @unless ($group->isSecret())
                            <a class="tag" href="{{ route('groups.show', [$group]) }}">

                                @if ($group->isOpen())
                                    <i class="fa fa-globe" title="{{ t('Public group') }}"></i>
                                @elseif ($group->isClosed())
                                    <i class="fa fa-lock" title="{{ t('Closed group') }}"></i>
                                @endif
                                {{ $group->name }}

                            </a>
                        @endunless
                    @endforeach
                </div>

                <div class="mb-3">
                    @if ($user->locationDisplay('long'))
                        <div class="fw-bold">{{ t('Location') }}</div>
                        {{ $user->locationDisplay('long') }}
                    @endif
                </div>

            </div>
        </div>

    @endauth
@endsection
