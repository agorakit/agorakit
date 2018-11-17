@extends('app')

@section('content')
  @include('groups.tabs')



  <div class="toolbox d-md-flex">
    @can('invite', $group)
      <div class="mb-2 mr-2">
        <a class="btn btn-primary" href="{{ action('InviteController@invite', $group ) }}"><i class="fa fa-plus"></i> {{trans('membership.invite_by_email')}}</a>
      </div>
    @endcan

    @can('edit-membership', $group)
      <div>
        <a class="btn btn-secondary" href="{{ action('Admin\MembershipController@create', $group ) }}"><i class="fa fa-plus"></i> {{trans('membership.directly_add_users_button')}}</a>
      </div>
    @endcan
  </div>


  <table class="table table-hover">
    <tr>
      <th>{{ trans('messages.name') }}</th>
      <th>{{ trans('messages.member_since') }}</th>
      <th>{{ trans('messages.last_activity') }}</th>
      <th>{{ trans('messages.status') }}</th>
      <th>{{ trans('messages.notifications_interval') }}</th>
      <th></th>
    </tr>


    @foreach( $memberships as $membership )
      <tr>
        <td>
          <a href="{{ route('users.show', $membership->user) }}"> <span class="avatar"><img src="{{route('users.cover', [$membership->user, 'small'])}}" class="rounded-circle"/></span> {{ $membership->user->name }}</a>
        </td>

        <td>
          <a href="{{ route('users.show', $membership->user) }}">{{ $membership->created_at->diffForHumans() }}</a>
        </td>

        <td>
          <a href="{{ route('users.show', $membership->user) }}">{{ $membership->user->updated_at->diffForHumans() }}</a>
        </td>

        @can('edit-membership', $group)

          <td>

            @if ($membership->membership == \App\Membership::ADMIN)
              Admin
            @endif
            @if ($membership->membership == \App\Membership::MEMBER)
              Member
            @endif
            @if ($membership->membership == \App\Membership::CANDIDATE)
              Candidate
            @endif
            @if ($membership->membership == \App\Membership::INVITED)
              Invited
            @endif
            @if ($membership->membership == \App\Membership::UNREGISTERED)
              Unregistered
            @endif
            </td>

            <td>
              {{minutesToInterval($membership->notification_interval)}}
            </td>
          @endcan

          <td>
            @can('edit-membership', $group)

              <div class="dropdown">
                <a type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fa fa-cog"></i> <span class="caret"></span>
                </a>
                <div class="dropdown-menu">


                  @if($membership->user->isAdminOf($group))
                    <a class="dropdown-item" href="{{action('Admin\AdminMembershipController@destroy', [$group, $membership->user])}}" onclick="return confirm('{{trans('messages.are_you_sure')}}');">
                      <i class="fa fa-trash-o"></i> {{trans('messages.remove_user_admin')}}
                    </a>
                  @else
                    <a class="dropdown-item" href="{{action('Admin\AdminMembershipController@store', [$group, $membership->user])}}" onclick="return confirm('{{trans('messages.are_you_sure')}}');">
                      <i class="fa fa-key"></i> {{trans('messages.make_user_admin')}}
                    </a>
                  @endif

                  <a class="dropdown-item" href="{{action('Admin\MembershipController@destroy', [$group, $membership->user])}}" onclick="return confirm('{{trans('messages.are_you_sure')}}');">
                    <i class="fa fa-ban"></i> {{trans('messages.remove_user')}}
                  </a>



                </div>
              </div>

            @endcan

          </td>

        </tr>
      @endforeach
    </table>

    {!! $memberships->render() !!}


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



    @can('edit-membership', $group)

      @if ($candidates->count() > 0)
        <h3 class="mt-5">{{trans('messages.candidates')}} :</h3>
        <table class="table">
          @foreach ($candidates as $candidate)
            <tr>
              <td>
                <a href="{{ route('users.show', $candidate) }}">
                  <span class="avatar"><img src="{{route('users.cover', [$candidate, 'small'])}}" class="rounded-circle"/></span>
                  {{ $candidate->name }}
                </a>
              </td>
              <td>
                <a class="btn btn-secondary" href="{{action('Admin\MembershipController@confirm', [$group, $candidate])}}" onclick="return confirm('{{trans('messages.are_you_sure')}}');">
                  <i class="fa fa-check"></i> {{trans('messages.confirm_user')}}
                </a>

                <a class="btn btn-secondary" href="{{action('Admin\MembershipController@destroy', [$group, $candidate])}}" onclick="return confirm('{{trans('messages.are_you_sure')}}');">
                  <i class="fa fa-ban"></i> {{trans('messages.remove_user')}}
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
