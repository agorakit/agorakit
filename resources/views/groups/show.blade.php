@extends('app')

@section('content')

  @include('groups.tabs')

  <div class="tab_content">

    <div class="row">
      <div class="col-md-6">
        <p>
          {!! filter($group->body) !!}
        </p>

        <p>
          @if (isset($admins) && $admins->count() > 0)
            {{trans('messages.group_admin_users')}} :

            @foreach ($admins as $admin)
              <a href="{{ route('users.show', [$admin]) }}">{{$admin->name}}</a>
            @endforeach
          @endif
        </p>

        @if ($group_email)
          {{trans('messages.email')}} : <a href="mailto:{{$group_email}}">{{$group_email}}</a>
        @endif

        <p>
          <span class="badge badge-secondary"><i class="fa fa-users"></i> {{$group->users()->count()}}</span>
          <span class="badge badge-secondary"><i class="fa fa-comments"></i> {{$group->discussions()->count()}}</span>
          <span class="badge badge-secondary"><i class="fa fa-calendar"></i> {{$group->actions()->count()}}</span>
          @foreach ($group->tags as $tag)
              <a href="{{route('tags.show', $tag)}}" class="badge badge-primary">{{$tag->name}}</a>
          @endforeach
        </p>

        <p>
          @can('history', $group)
            @if ($group->revisionHistory->count() > 0)
              <a href="{{route('groups.history', $group)}}">
                <i class="fa fa-history"></i>
                {{trans('messages.show_history')}}
              </a>
            @endif
          @endcan
        </p>
      </div>

      <div class="col-md-6">
        @if ($group->hasCover())
          <img class="img-fluid rounded" src="{{route('groups.cover.large', $group)}}"/>
        @else
          <img class="img-fluid rounded" src="/images/group.svg"/>
        @endif
      </div>

    </div>


    <div class="row mt-5">


      @if ($discussions)
        @if($discussions->count() > 0)
          <div class="col-md-7">
            <h2 class="mb-4">
              <a href="{{ route('groups.discussions.index', $group) }}">{{trans('group.latest_discussions')}}</a>
              @can('create-discussion', $group)
                <a class="btn btn-sm btn-primary" href="{{ route('groups.discussions.create', $group) }}">
                  <i class="fa fa-plus"></i> {{trans('discussion.create_one_button')}}
                </a>
              @endcan
            </h2>
            <div class="discussions">
              @foreach( $discussions as $discussion )
                @include('discussions.discussion')
              @endforeach
            </div>
          </div>
        @endif
      @endif

      @if ($actions)
        @if($actions->count() > 0)
          <div class="col-md-5">
            <h2 class="mb-4">
              <a href="{{ route('groups.actions.index', $group) }}">{{trans('messages.agenda')}}</a>
              @can('create-action', $group)
                <a class="btn btn-sm btn-primary" href="{{ route('groups.actions.create', $group ) }}">
                  <i class="fa fa-plus"></i> {{trans('action.create_one_button')}}
                </a>
              @endcan
            </h2>
            <div class="actions">
              @foreach( $actions as $action )
                @include('actions.action')
              @endforeach
            </div>
          </div>
        @endif
      @endif

    </div>


    @if ($files)
      @if($files->count() > 0)
        <h2 class="mb-4 mt-5"><a href="{{ route('groups.files.index', $group) }}">{{trans('group.latest_files')}}</a></h2>
        <div class="files">
          @forelse( $files as $file )
            @include('files.file')
          @endforeach
        </div>
      @endif
    @endif


    @if ($activities)
      @if($activities->count() > 0)
        <h2>{{trans('messages.recent_activity')}}</h2>
        @each('partials.activity-small', $activities, 'activity')
      @endif
    @endif

  </div>

@endsection
