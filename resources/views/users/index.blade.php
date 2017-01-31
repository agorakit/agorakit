@extends('app')

@section('content')
    @include('partials.grouptab')
    <div class="tab_content">

        @include('partials.invite')

        <h2>
            {{ trans('messages.members_of_this_group') }}
            @can('invite', $group)
                <a class="btn btn-primary btn-xs" href="{{ action('InviteController@invite', $group->id ) }}"><i class="fa fa-plus"></i> {{trans('membership.invite_one_button')}}</a>
            @endcan

            @can('add-members', $group)
                <a class="btn btn-warning btn-xs" href="{{ action('MembershipController@addUserForm', $group->id ) }}"><i class="fa fa-plus"></i> {{trans('membership.directly_add_users_button')}}</a>
            @endcan

            <a class="btn btn-primary btn-xs" href="{{ action('MapController@map', $group ) }}">{{trans('messages.show_map')}}</a>
        </h2>


        <table class="table table-hover">
            <tr>
                <th>{{ trans('messages.name') }}</th>
                <th>{{ trans('messages.registration_time') }}</th>
                <th></th>
            </tr>

            @can('edit-membership', $group)
                @foreach( $admins as $user )
                    <tr>
                        <td>
                            <a href="{{ action('UserController@show', $user->id) }}">
                                <span class="avatar"><img src="{{$user->avatar()}}" class="img-circle"/></span>
                                {{ $user->name }} ({{trans('messages.admin')}})
                            </a>
                        </td>

                        <td>
                            <a href="{{ action('UserController@show', $user->id) }}">{{ $user->created_at->diffForHumans() }}</a>
                        </td>


                        <td>
                            @can('edit-membership', $group)
                                <a href="{{ action('MembershipController@adminForm', [$group, $user]) }}" class="btn btn-warning btn-sm">{{trans('messages.edit')}}</a>
                            @endcan

                        </td>

                    </tr>

                @endforeach

            @endcan

            @foreach( $users as $user )
                <tr>
                    <td>
                        <a href="{{ action('UserController@show', $user->id) }}"> <span class="avatar"><img src="{{$user->avatar()}}" class="img-circle"/></span> {{ $user->name }}</a>
                    </td>

                    <td>
                        <a href="{{ action('UserController@show', $user->id) }}">{{ $user->created_at->diffForHumans() }}</a>
                    </td>


                    <td>
                        @can('edit-membership', $group)
                            <a href="{{ action('MembershipController@adminForm', [$group, $user]) }}" class="btn btn-warning btn-sm">{{trans('messages.edit')}}</a>
                        @endcan

                    </td>

                </tr>
            @endforeach
        </table>

        {!! $users->render() !!}


    </div>



@endsection
