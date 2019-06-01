@extends('app')

@section('content')


  @include('users.tabs')

  <div class="tab_content">

    @if (Auth::user())

      <div class="row">
        <div class="col-md-8">

          <div class="meta">
            {{trans('messages.registered')}} :  {{ $user->created_at->diffForHumans() }}
          </div>

          <div class="mb-3">
            @foreach ($user->groups as $group)
              @unless ($group->isSecret())
                <a href="{{ route('groups.show', [$group]) }}" class="badge badge-secondary">

                  @if ($group->isOpen())
                    <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
                  @elseif ($group->isClosed())
                    <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
                  @endif
                  {{ $group->name }}

                </a>
              @endunless
            @endforeach



            @if ($user->tags->count() > 0)
              @foreach ($user->tags as $tag)
                @include('tags.tag')
              @endforeach
            @endif
          </div>


          {!! filter($user->body) !!}

        </div>

        <div class="col-md-4">
          <img src="{{route('users.cover', [$user, 'medium'])}}" class="img-fluid rounded-circle" style="width: 100%"/>
        </div>
      </div>

    @endif

  </div>
@endsection
