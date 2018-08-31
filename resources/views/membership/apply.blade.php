@extends('dialog')

@section('content')



  <h1>{{trans('membership.apply_for_group')}} <em>{{$group->name}}</em></h1>



  <div class="help">
    <h4>{{trans('messages.how_does_it_work')}}</h4>
    <p>
      {{trans('membership.apply_intro')}}
    </p>
  </div>

  {!! Form::open(array('action' => ['MembershipController@store', $group])) !!}

  <div class="mt-5 d-flex justify-content-between align-items-center">
    <a class="mr-5" href="{{url('/')}}">{{trans('messages.cancel')}}</a>
    {!! Form::submit(trans('membership.apply'), ['class' => 'btn btn-primary form-control']) !!}

  </div>


  {!! Form::close() !!}




@endsection
