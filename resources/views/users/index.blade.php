@extends('app')

@section('content')
    @include('groups.tabs')



    <div class="toolbox d-md-flex">
        @can('invite', $group)
            <div class="mb-2 mr-2">
                <a class="btn btn-primary" href="{{ action('InviteController@invite', $group->id ) }}"><i class="fa fa-plus"></i> {{trans('membership.invite_one_button')}}</a>
            </div>
        @endcan

        @can('edit-membership', $group)
            <div>
                <a class="btn btn-secondary" href="{{ action('Admin\MembershipController@addUserForm', $group->id ) }}"><i class="fa fa-plus"></i> {{trans('membership.directly_add_users_button')}}</a>
            </div>
        @endcan
    </div>


    <table class="table table-hover">
        <tr>
            <th>{{ trans('messages.name') }}</th>
            <th>{{ trans('messages.registration_time') }}</th>
            <th>{{ trans('messages.last_activity') }}</th>
            <th></th>
        </tr>


        @foreach( $users as $user )
            <tr>
                <td>
                    <a href="{{ route('users.show', $user->id) }}"> <span class="avatar"><img src="{{$user->avatar()}}" class="rounded-circle"/></span> {{ $user->name }}</a>
                </td>

                <td>
                    <a href="{{ route('users.show', $user->id) }}">{{ $user->created_at->diffForHumans() }}</a>
                </td>

                <td>
                    <a href="{{ route('users.show', $user->id) }}">{{ $user->updated_at->diffForHumans() }}</a>
                </td>


                <td>
                    @can('edit-membership', $group)

                        <div class="dropdown">
                            <a type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-wrench"></i> <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu">


                                @if($user->isAdminOf($group))
                                    <a class="dropdown-item" href="{{action('Admin\MembershipController@removeAdminUser', [$group, $user])}}" onclick="return confirm('{{trans('messages.are_you_sure')}}');">
                                        <i class="fa fa-trash-o"></i> {{trans('messages.remove_user_admin')}}
                                    </a>
                                @else
                                    <a class="dropdown-item" href="{{action('Admin\MembershipController@addAdminUser', [$group, $user])}}" onclick="return confirm('{{trans('messages.are_you_sure')}}');">
                                        <i class="fa fa-key"></i> {{trans('messages.make_user_admin')}}
                                    </a>
                                @endif

                                <a class="dropdown-item" href="{{action('Admin\MembershipController@removeUser', [$group, $user])}}" onclick="return confirm('{{trans('messages.are_you_sure')}}');">
                                    <i class="fa fa-ban"></i> {{trans('messages.remove_user')}}
                                </a>



                            </div>
                        </div>

                    @endcan

                </td>

            </tr>
        @endforeach
    </table>

    {!! $users->render() !!}


    @if ($admins->count() > 0)
        <h3 class="mt-5">{{trans('messages.admins')}} : </h3>
        <table class="table">
            @foreach( $admins as $admin )
                <tr>
                    <td>
                        <a href="{{ route('users.show', $admin) }}">
                            <span class="avatar"><img src="{{$admin->avatar()}}" class="rounded-circle"/></span>
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
                                <span class="avatar"><img src="{{$candidate->avatar()}}" class="rounded-circle"/></span>
                                {{ $candidate->name }}
                            </a>
                        </td>
                        <td>
                            <a class="btn btn-secondary" href="{{action('Admin\MembershipController@confirm', [$group, $candidate])}}" onclick="return confirm('{{trans('messages.are_you_sure')}}');">
                                <i class="fa fa-check"></i> {{trans('messages.confirm_user')}}
                            </a>

                            <a class="btn btn-secondary" href="{{action('Admin\MembershipController@removeUser', [$group, $candidate])}}" onclick="return confirm('{{trans('messages.are_you_sure')}}');">
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
