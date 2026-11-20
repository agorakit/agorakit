@extends('dialog')

@section('content')
    <h1>{{ t('Apply for the group') }} <em>{{ $group->name }}</em></h1>

    @if (Auth::user()->isCandidateOf($group))
        <div class="help" role="alert">
            {{ t('You have asked to join this group. You must now wait for an admin\'s response.') }}
        </div>

        <a class="js-back btn btn-primary " href="{{ url('/') }}" up-dismiss>Ok</a>
    @else
        <div class="help">
            <h4>{{ t('How does it work?') }}</h4>
            <p>
                {{ t('This group is closed so you need to apply using the button below. An admin will be notified and will act accordingly.') }}
            </p>
        </div>

        {!! Form::open(['action' => ['GroupMembershipController@store', $group]]) !!}

        <div class="mt-5 d-flex justify-content-between align-items-center">

            {!! Form::submit(t('Apply'), ['class' => 'btn btn-primary']) !!}
            <a class="js-back" href="{{ url('/') }}" up-dismiss>{{ t('Cancel') }}</a>

        </div>


        {!! Form::close() !!}
    @endif
@endsection
