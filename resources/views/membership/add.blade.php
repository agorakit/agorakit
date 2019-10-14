@extends('app')


@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        <h2>{{trans('membership.add_users')}}</h2>

        <p>
            {{trans('membership.add_users_intro')}}
        </p>


        {!! Form::open(array('action' => ['MassMembershipController@store', $group])) !!}

        <div class="form-group">
            {!! Form::label('users', trans('membership.users_to_add')) !!}
            {!! Form::select('users[]', $notmembers, null, ['multiple' => true, 'class' => 'form-control tags', 'required', 'id' =>'users']) !!}
        </div>

        <div class="form-group">
            {!! Form::submit(trans('membership.add_button'), ['class' => 'btn btn-primary btn-lg']) !!}
            <a href="{{url('/')}}">{{trans('messages.cancel')}}</a>
        </div>


        {!! Form::close() !!}


    </div>


@endsection
