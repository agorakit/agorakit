@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        <h1>{{trans('messages.join_the_group')}} <em>{{$group->name}}</em></h1>

        <div class="help">
            <h4>{{trans('messages.how_does_it_work')}}</h4>
            <p>
                {{trans('membership.join_intro')}}
            </p>

        </div>


        {!! Form::open(array('action' => ['MembershipController@store', $group->id])) !!}

        @include('membership.form')

        <div class="form-group">
            {!! Form::submit(trans('membership.join_button'), ['class' => 'btn btn-primary form-control']) !!}
            <a href="{{url('/')}}">{{trans('messages.cancel')}}</a>
        </div>


        {!! Form::close() !!}

        
    </div>


@endsection
