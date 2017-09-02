@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        <h1>{{trans('messages.admin_of')}} "{{$user->name}}"</h1>



        @can('edit-membership', $group)
            {!! Form::open(['action'=> ['AdminMembershipController@removeUser', $group, $user], 'method'=>'DELETE', 'role'=>'form','onsubmit' => 'return confirm("'. trans('messages.are_you_sure') . '")' ])!!}
            <button type="submit" name="button" class="btn btn-warning btn-sm">
                <i class="fa fa-star"></i> {{trans('messages.remove_user')}}
            </button>
            {!! Form::close() !!}
        @endcan


        @can('edit-membership', $group)
            @unless($user->isAdminOf($group))
                {!! Form::open(['action'=> ['AdminMembershipController@addAdminUser', $group, $user], 'method'=>'POST', 'role'=>'form','onsubmit' => 'return confirm("'. trans('messages.are_you_sure') . '")' ])!!}
                <button type="submit" name="button" class="btn btn-warning btn-sm">
                    <i class="fa fa-star"></i> {{trans('messages.make_user_admin')}}
                </button>
                {!! Form::close() !!}
            @endunless
        @endcan


        @can('edit-membership', $group)
            @if($user->isAdminOf($group))
                {!! Form::open(['action'=> ['AdminMembershipController@removeAdminUser', $group, $user], 'method'=>'DELETE', 'role'=>'form','onsubmit' => 'return confirm("'. trans('messages.are_you_sure') . '")' ])!!}
                <button type="submit" name="button" class="btn btn-warning btn-sm">
                    <i class="fa fa-star-o"></i> {{trans('messages.remove_user_admin')}}
                </button>
                {!! Form::close() !!}
            @endif
        @endcan






        @include('partials.errors')




    </div>

@endsection
