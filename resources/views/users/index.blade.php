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
                <a class="btn btn-warning" href="{{ action('MembershipAdminController@addUserForm', $group->id ) }}"><i class="fa fa-plus"></i> {{trans('membership.directly_add_users_button')}}</a>
            @endcan

            <a class="btn btn-primary" href="{{ action('MapController@map', $group ) }}">{{trans('messages.show_map')}}</a>

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
                        <a href="{{ action('UserController@show', $user->id) }}"> <span class="avatar"><img src="{{$user->avatar()}}" class="img-circle"/></span> {{ $user->name }}</a>
                    </td>

                    <td>
                        <a href="{{ action('UserController@show', $user->id) }}">{{ $user->created_at->diffForHumans() }}</a>
                    </td>

                    <td>
                        <a href="{{ action('UserController@show', $user->id) }}">{{ $user->updated_at->diffForHumans() }}</a>
                    </td>


                    <td>
                        @can('edit-membership', $group)
                            <a href="{{ action('MembershipAdminController@editUserForm', [$group, $user]) }}" class="btn btn-warning btn-sm">{{trans('messages.edit')}}</a>
                        @endcan

                    </td>

                </tr>
            @endforeach
        </table>

        {!! $users->render() !!}


    </div>



@endsection
