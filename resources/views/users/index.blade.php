@extends('app')



@section('content')
  @include('groups.tabs')



  <div class="toolbox d-md-flex">
    @can('invite', $group)
      <div class="mb-2 mr-2">
        <a class="btn btn-primary" href="{{ action('InviteController@invite', $group ) }}"><i class="fa fa-plus"></i> {{trans('membership.invite_by_email')}}</a>
      </div>
    @endcan

    @can('manage-membership', $group)
      <div>
        <a class="btn btn-secondary" href="{{ action('MassMembershipController@create', $group ) }}"><i class="fa fa-plus"></i> {{trans('membership.directly_add_users_button')}}</a>
      </div>
    @endcan
  </div>


  <table style="width: 100%" class="table data-table table-striped">
    <thead class="thead-dark" style="width: 100%">
      <tr>
        <th data-priority="1">{{ trans('messages.name') }}</th>
        <th data-priority="3">{{ trans('messages.member_since') }}</th>
        <th data-priority="4">{{ trans('messages.last_activity') }}</th>

        @can('manage-membership', $group)
          <th data-priority="2">{{ trans('messages.email') }}</th>
          <th data-priority="2">{{ trans('messages.status') }}</th>
          <th data-priority="2">{{ trans('messages.notifications_interval') }}</th>
          <th data-priority="1"></th>
        @endcan

      </tr>
    </thead>


    <tbody>
      @foreach( $memberships as $membership )
        <tr>
          <td>
            <a href="{{ route('users.show', $membership->user) }}"> <span class="avatar"><img src="{{route('users.cover', [$membership->user, 'small'])}}" class="rounded-circle"/></span> {{ $membership->user->name }}</a>
          </td>

          <td data-order="{{$membership->created_at}}">
            <a href="{{ route('users.show', $membership->user) }}">{{ $membership->created_at->diffForHumans() }}</a>
          </td>

          <td data-order="{{ $membership->user->updated_at}}">
            <a href="{{ route('users.show', $membership->user) }}">{{ $membership->user->updated_at->diffForHumans() }}</a>
          </td>

          @can('manage-membership', $group)
            <td>
               {{$membership->user->email}}
            </td>

            <td data-order="{{ $membership->membership }}">

              @if ($membership->membership == \App\Membership::ADMIN)
                {{trans('membership.admin')}}
              @endif
              @if ($membership->membership == \App\Membership::MEMBER)
                {{trans('membership.member')}}
              @endif
              @if ($membership->membership == \App\Membership::CANDIDATE)
                {{trans('membership.candidate')}}
              @endif
              @if ($membership->membership == \App\Membership::INVITED)
                {{trans('membership.invited')}}
              @endif
              @if ($membership->membership == \App\Membership::UNREGISTERED)
                {{trans('membership.unregistered')}}
              @endif
              @if ($membership->membership == \App\Membership::REMOVED)
                {{trans('membership.removed')}}
              @endif
              @if ($membership->membership == \App\Membership::BLACKLISTED)
                {{trans('membership.blacklisted')}}
              @endif

            </td>

            <td data-order="{{ $membership->notification_interval }}">
              {{minutesToInterval($membership->notification_interval)}}
            </td>

            <td>
              <a class="btn btn-secondary" href="{{action('MembershipController@edit', [$group, $membership])}}">{{trans('messages.edit')}}</a>
            </td>
          @endcan

        </tr>
      @endforeach

      <tbody>

      </table>


      @if ($admins->count() > 0)
        <h3 class="mt-5">{{trans('messages.admins')}} : </h3>
        <table class="table">
          @foreach( $admins as $admin )
            <tr>
              <td>
                <a href="{{ route('users.show', $admin) }}">
                  <span class="avatar"><img src="{{route('users.cover', [$admin, 'small'])}}" class="rounded-circle"/></span>
                  {{ $admin->name }}
                </a>
              </td>
            </tr>
          @endforeach
        </table>
      @endif



      @can('manage-membership', $group)

        @if ($candidates->count() > 0)
          <h3 class="mt-5">{{trans('messages.candidates')}} :</h3>
          <table class="table">
            @foreach ($candidates as $candidate)
              <tr>
                <td>
                  <a href="{{ route('users.show', $candidate->user) }}">
                    <span class="avatar"><img src="{{route('users.cover', [$candidate->user, 'small'])}}" class="rounded-circle"/></span>
                    {{ $candidate->user->name }}
                  </a>
                </td>
                <td>
                  <a class="btn btn-secondary" href="{{action('MembershipController@edit', [$group, $candidate])}}">
                    <i class="fa fa-check"></i> {{trans('messages.edit')}}
                  </a>

                </td>

              </tr>
            @endforeach
          </table>
        @endif

        @if ($invites->count() > 0)
          <h3 class="mt-5">{{trans('messages.user_invited')}} :</h3>
          <table class="table">
            @foreach ($invites as $invite)
              <tr>
                <td>{{$invite->email}}</td>
                <td>{{$invite->created_at}}</td>
                <!--<td>Resend invite TODO</td>-->
              </tr>
            @endforeach
          </table>
        @endif

      @endcan


    @endsection
