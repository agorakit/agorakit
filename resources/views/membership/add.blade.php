@extends('app')

@push('css')
    {!! Html::style('/packages/selectize/css/selectize.bootstrap3.css') !!}
@endpush

@push('js')
    {!! Html::script('/packages/selectize/js/standalone/selectize.min.js') !!}
    <script>
    $( document ).ready(function() {
        $('#users').selectize({
            maxItems: null
        });
    });
    </script>
@endpush



@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        <h2>{{trans('membership.add_users')}}</h2>

        <p>
            {{trans('membership.add_users_intro')}}
        </p>


        {!! Form::open(array('action' => ['MembershipAdminController@addUser', $group])) !!}

        <div class="form-group">
            {!! Form::label('users', trans('membership.users_to_add')) !!}
            {!! Form::select('users[]', $notmembers, null, ['multiple' => true, 'class' => 'form-control', 'required', 'id' =>'users']) !!}
        </div>

        <div class="form-group">
            {!! Form::submit(trans('membership.add_button'), ['class' => 'btn btn-primary form-control']) !!}
            <a href="{{url('/')}}">{{trans('messages.cancel')}}</a>
        </div>


        {!! Form::close() !!}


    </div>


@endsection
