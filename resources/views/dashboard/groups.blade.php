@extends('app')

@section('content')


    <h1><a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.all_groups') }}</h1>



    @if ($groups)
        <div class="row mb-3">
            @forelse( $groups as $group )
                <div class="col-md-4">
                    <div class="card tag-group @foreach ($group->tags as $tag)tag-{{$tag->tag_id}} @endforeach">

                        <a href="{{ action('GroupController@show', $group) }}">
                            <img class="card-img-top" src="{{ route('groups.cover', $group)}}" />
                        </a>

                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ action('GroupController@show', $group) }}">
                                    {{ $group->name }}
                                </a>
                                @if ($group->isOpen())
                                    <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
                                @elseif ($group->isClosed())
                                    <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
                                @else
                                    <i class="fa fa-eye-slash" title="{{trans('group.secret')}}"></i>
                                @endif
                            </h5>
                            <p class="card-text">
                                {{summary($group->body) }}
                                <br/>
                                @foreach ($group->tags as $tag)
                                    <span class="badge tag">{{$tag->name}}</span>
                                @endforeach
                            </p>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a class="btn btn-outline-secondary" href="{{ action('GroupController@show', $group) }}"></i>
                                        {{ trans('messages.visit') }}
                                    </a>

                                    @unless ($group->isMember())
                                        @can ('join', $group)
                                            <a class="btn btn-outline-secondary" href="{{ action('MembershipController@store', $group->id) }}">
                                                {{ trans('group.join') }}
                                            </a>
                                        @endcan
                                    @endunless
                                </div>
                                <small class="text-muted">{{ $group->updated_at->diffForHumans() }}</small>
                            </div>



                        </div>
                    </div>
                </div>



                @if ($loop->iteration % 3 == 0)
                </div>
                <div class="row mb-3">
                @endif


            @empty
                {{trans('messages.nothing_yet')}}
            @endforelse

        </div>
    @else
        {{trans('messages.nothing_yet')}}
    @endif
</div>





</div>

@endsection
