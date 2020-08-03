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
        <a class="btn btn-secondary" href="{{ action('GroupMassMembershipController@create', $group ) }}"><i class="fa fa-plus"></i> {{trans('membership.directly_add_users_button')}}</a>
      </div>
    @endcan
  </div>


  <table style="width: 100%" class="table data-table table-striped" data-order='[[ 3, "desc" ], [ 0, "asc" ]]'>
    <thead class="thead-dark" style="width: 100%">
      <tr>
        <th data-priority="1">{{ trans('messages.name') }}</th>
        <th data-priority="3">{{ trans('messages.member_since') }}</th>
        <th data-priority="4">{{ trans('messages.last_activity') }}</th>
        <th data-priority="2">{{ trans('messages.status') }}</th>

      </tr>
    </thead>


    <tbody>
      @foreach( $memberships as $membership )
        <tr>
          <td>
            <a up-follow href="{{ route('users.show', $membership->user) }}"> <span class="avatar"><img src="{{route('users.cover', [$membership->user, 'small'])}}" class="rounded-circle"/></span> {{ $membership->user->name }}</a>
          </td>

          <td data-order="{{$membership->created_at}}">
            <a up-follow href="{{ route('users.show', $membership->user) }}">{{ $membership->created_at->diffForHumans() }}</a>
          </td>

          <td data-order="{{ $membership->user->updated_at}}">
            <a up-follow href="{{ route('users.show', $membership->user) }}">{{ $membership->user->updated_at->diffForHumans() }}</a>
          </td>


          <td data-order="{{ $membership->membership }}">

            @if ($membership->membership == \App\Membership::ADMIN)
              <span class="badge badge-pill badge-success" up-tooltip="@lang('This member is admin of the group and manages it')">
                {{trans('membership.admin')}}
              </span>
            @endif
            @if ($membership->membership == \App\Membership::MEMBER)
              <span class="badge badge-pill badge-primary" up-tooltip="@lang('Regular member of the group')">
                {{trans('membership.member')}}
              </span>
            @endif
            @if ($membership->membership == \App\Membership::CANDIDATE)
              <span class="badge badge-pill badge-info" up-tooltip="@lang('This user asked to be part of the group but has not yet been accepted')">
                {{trans('membership.candidate')}}
              </span>
            @endif
            @if ($membership->membership == \App\Membership::INVITED)
              <span class="badge badge-pill badge-secondary" up-tooltip="@lang('This user has been invited to the group but did not accept yet')">
                {{trans('membership.invited')}}
              </span>
            @endif

            @if ($membership->membership == \App\Membership::DECLINED)
              <span class="badge badge-pill badge-dark" up-tooltip="@lang('This member declined the invitagion the group')">
                {{trans('membership.declined')}}
              </span>
            @endif


            @if ($membership->membership == \App\Membership::UNREGISTERED)
              <span class="badge badge-pill badge-dark" up-tooltip="@lang('This member left the group')">
                {{trans('membership.unregistered')}}
              </span>
            @endif
            @if ($membership->membership == \App\Membership::REMOVED)
              <span class="badge badge-pill badge-dark" up-tooltip="@lang('This member has been removed from the group')">
                {{trans('membership.removed')}}
              </span>
            @endif
            @if ($membership->membership == \App\Membership::BLACKLISTED)
              <span class="badge badge-pill badge-danger" up-tooltip="@lang('This member has been blacklisted')">
                {{trans('membership.blacklisted')}}
              </span>
            @endif

          </td>

          @can('manage-membership', $group)
            <td>
              <a class="btn btn-primary btn-sm" href="{{action('GroupMembershipController@edit', [$group, $membership])}}">{{trans('messages.edit')}}</a>
            </td>
          @endcan

        </tr>
      @endforeach

      <tbody>

      </table>


    @endsection
