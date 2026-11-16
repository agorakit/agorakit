@extends('dialog')

@section('content')
    <h1>{{ trans('Join the group') }} <em>"{{ $group->name }}"</em></h1>

    <div class="help">
        <h4>{{ trans('How does it work?') }}</h4>
        <p>
            {{ trans('Here you can choose if you want to receive news from this group by email.') }}
        </p>

    </div>


    {!! Form::open(['action' => ['GroupMembershipController@store', $group]]) !!}

    @include('membership.form')

    <div class="mt-5 d-flex justify-content-between align-items-center">

        {!! Form::submit(trans('Join'), ['class' => 'btn btn-primary px-3']) !!}
        <a class="js-back" href="{{ url('/') }}" up-dismiss>{{ trans('Cancel') }}</a>

    </div>
@endsection
