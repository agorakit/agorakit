@extends('dialog')

@section('content')

<h1>{{trans('membership.apply_for_group')}} <em>{{$group->name}}</em></h1>

@if(Auth::user()->hasLevel(\App\Membership::CANDIDATE, $group))
<div class="help" role="alert">
    {{trans('membership.group_candidate_info')}}
</div>

<a class="js-back btn btn-primary " href="{{url('/')}}">Ok</a>

@else





<div class="help">
    <h4>{{trans('messages.how_does_it_work')}}</h4>
    <p>
        {{trans('membership.apply_intro')}}
    </p>
</div>

{!! Form::open(array('action' => ['GroupMembershipController@store', $group])) !!}

<div class="mt-5 flex justify-between items-center">

    {!! Form::submit(trans('membership.apply'), ['class' => 'btn btn-primary btn-lg']) !!}
    <a class="js-back" href="{{url('/')}}">{{trans('messages.cancel')}}</a>

</div>


{!! Form::close() !!}

@endif


@endsection