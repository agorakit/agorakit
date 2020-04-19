@extends('app')

@section('content')

  @include('groups.tabs')

  <div class="tab_content">

    <div class="row">
      <div class="col-md-6">
        <div>
          {!! filter($group->body) !!}
        </div>


        @if (isset($admins) && $admins->count() > 0)
          <div class="mb-3">
            <div class="font-weight-bold">
              {{trans('messages.group_admin_users')}}
            </div>

            @foreach ($admins as $admin)
              <a class="mr-2" href="{{ route('users.show', [$admin]) }}">{{$admin->name}}</a>
            @endforeach
          </div>
        @endif


        @if ($group_inbox)
          <div class="mb-3">
            <div class="font-weight-bold">{{__('Inbox for this group')}}</div>
            <a href="mailto:{{$group_inbox}}">{{$group_inbox}}</a>
          </div>
        @endif

        <div class="mb-3">
          <div class="font-weight-bold">{{__('Creation date')}}</div>
          {{$group->created_at->diffForHumans()}}
        </div>

        <div class="mb-3">
          <div class="font-weight-bold">{{__('Group type')}}</div>
          @if ($group->isOpen())
            <i class="fa fa-globe" title="{{trans('group.open')}}">
            </i>
            @lang('This group is public and open to members')
          @elseif ($group->isClosed())
            <i class="fa fa-lock" title="{{trans('group.closed')}}">
            </i>
            @lang('This group is private. Users must apply to join')
          @else
            <i class="fa fa-eye-slash" title="{{trans('group.secret')}}"></i>
            @lang('This group is secret and only visible to it\'s members')
          @endif

        </div>

        <div class="mb-3">
          <div class="font-weight-bold">{{__('Stats & keywords')}}</div>
          <span class="badge badge-secondary"><i class="fa fa-users"></i> {{$group->users()->count()}}</span>
          <span class="badge badge-secondary"><i class="fa fa-comments"></i> {{$group->discussions()->count()}}</span>
          <span class="badge badge-secondary"><i class="fa fa-calendar"></i> {{$group->actions()->count()}}</span>
          @foreach ($group->tags as $tag)
            @include('tags.tag')
          @endforeach
        </div>
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
