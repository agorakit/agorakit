@extends('app')

@section('content')

    @include('partials.grouptab')

    <div class="tab_content">

        <h1>{{trans('messages.admin_of')}} "{{$user->name}}"</h1>



        @can('edit-membership', $group)
            {!! Form::open(['action'=> ['MembershipController@removeUser', $group, $user], 'method'=>'DELETE', 'role'=>'form','onsubmit' => 'return confirm("are you sure ?")'])!!}
            <button type="submit" name="button" class="btn btn-warning btn-sm">
                <i class="fa fa-star"></i> {{trans('messages.remove_user')}}
            </button>
            {!! Form::close() !!}
        @endcan


        @can('edit-membership', $group)
            @unless($user->isAdminOf($group))
                {!! Form::open(['action'=> ['MembershipController@addAdminUser', $group, $user], 'method'=>'POST', 'role'=>'form','onsubmit' => 'return confirm("are you sure ?")'])!!}
                <button type="submit" name="button" class="btn btn-warning btn-sm">
                    <i class="fa fa-star"></i> {{trans('messages.make_user_admin')}}
                </button>
                {!! Form::close() !!}
            @endunless
        @endcan

        
        @can('edit-membership', $group)
            @if($user->isAdminOf($group))
                {!! Form::open(['action'=> ['MembershipController@removeAdminUser', $group, $user], 'method'=>'DELETE', 'role'=>'form','onsubmit' => 'return confirm("are you sure ?")'])!!}
                <button type="submit" name="button" class="btn btn-warning btn-sm">
                    <i class="fa fa-star-o"></i> {{trans('messages.remove_user_admin')}}
                </button>
                {!! Form::close() !!}
            @endif
        @endcan






        @include('partials.errors')




    </div>

@endsection
