<div class="col-md-4" up-expand>
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
                    @include('tags.tag')
                @endforeach
            </p>

            <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                    <a class="btn btn-primary" href="{{ action('GroupController@show', $group) }}"></i>
                        {{ trans('messages.visit') }}
                    </a>

                    @unless ($group->isMember())
                        @can ('join', $group)
                            <a class="btn btn-secondary" href="{{ action('MembershipController@store', $group) }}">
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
