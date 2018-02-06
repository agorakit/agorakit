@extends('app')

@section('content')
    @include('groups.tabs')
    <div class="tab_content">

        @include('partials.invite')


        <div class="toolbox">
            @can('invite', $group)
                <a class="btn btn-primary" href="{{ action('InviteController@invite', $group->id ) }}"><i class="fa fa-plus"></i> {{trans('membership.invite_one_button')}}</a>
            @endcan

            @can('edit-membership', $group)
                <a class="btn btn-secondary" href="{{ action('Admin\MembershipController@addUserForm', $group->id ) }}"><i class="fa fa-plus"></i> {{trans('membership.directly_add_users_button')}}</a>
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
            <strong>{{trans('messages.admins')}} : </strong>
            @foreach( $admins as $admin )
                <a href="{{ route('users.show', $admin) }}">{{ $admin->name }}</a>
            @endforeach
        @endif



        @can('edit-membership', $group)
            @if ($invites->count() > 0)
                {{trans('messages.user_invited')}} :
                @foreach ($invites as $invite)
                    <li>{{$invite->email}}</li>
                @endforeach
            @endif
        @endcan


    </div>



@endsection
