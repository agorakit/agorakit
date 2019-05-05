@extends('app')

@section('content')

    <div class="d-flex justify-content-between">

        <h1><a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.all_groups') }}</h1>


        <form class="form-inline my-2 my-lg-0" role="search" method="GET" action="{{route('groups.index')}}" up-autosubmit up-delay="250" up-target=".groups">
            <div class="input-group">
                <input value="{{Request::get('search')}}" class="form-control form-control-sm" type="text" name="search"  placeholder="{{trans('messages.search')}}..." aria-label="Search">

                <div class="input-group-append">
                    <button class="btn btn-outline-secondary btn-sm my-2 my-sm-0" type="submit"><span class="fa fa-search"></span></button>
                </div>
            </div>
        </form>

    </div>


    @if ($groups)
        <div class="row mb-3 groups">
            @forelse( $groups as $group )
                <div class="col-md-4">
                    <div class="card tag-group @foreach ($group->tags as $tag)tag-{{$tag->tag_id}} @endforeach">

                        <a href="{{ action('GroupController@show', $group) }}">
                            @if ($group->hasCover())
                                <img class="card-img-top" src="{{ route('groups.cover.medium', $group)}}" />
                            @else
                                <img class="card-img-top" src="/images/group.svg"/>
                            @endif
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

                                <span class="badge badge-secondary"><i class="fa fa-users"></i> {{$group->users()->count()}}</span>
                                <span class="badge badge-secondary"><i class="fa fa-comments"></i> {{$group->discussions()->count()}}</span>
                                <span class="badge badge-secondary"><i class="fa fa-calendar"></i> {{$group->actions()->count()}}</span>




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
                                            <a class="btn btn-outline-secondary" href="{{ action('MembershipController@store', $group) }}">
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
            {!!$groups->render()!!}
        </div>
    @else
        {{trans('messages.nothing_yet')}}
    @endif
</div>





</div>

@endsection
