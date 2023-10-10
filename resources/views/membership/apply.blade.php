@extends('dialog')

@section('content')
    <h1>{{ trans('membership.apply_for_group') }} <em>{{ $group->name }}</em></h1>

    @if (Auth::user()->isCandidateOf($group))
        <div class="help" role="alert">
            {{ trans('membership.group_candidate_info') }}
        </div>

        <a class="js-back btn btn-primary " href="{{ url('/') }}" up-dismiss>Ok</a>
    @else
        <div class="help">
            <h4>{{ trans('messages.how_does_it_work') }}</h4>
            <p>
                {{ trans('membership.apply_intro') }}
            </p>
        </div>

        {!! Form::open(['action' => ['GroupMembershipController@store', $group]]) !!}

        <div class="mt-5 d-flex justify-content-between align-items-center">

            {!! Form::submit(trans('membership.apply'), ['class' => 'btn btn-primary']) !!}
            <a class="js-back" href="{{ url('/') }}" up-dismiss>{{ trans('messages.cancel') }}</a>

        </div>


        {!! Form::close() !!}
    @endif
@endsection
