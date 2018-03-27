@extends('app')

@section('content')


  <h1>{{ trans('messages.leaving_the_group_called') }} <em>{{$group->name}}</em> ?</h1>


  <p>
    {{ trans('messages.leaving_help_message') }}
  </p>



  {!! Form::open(array('action' => ['MembershipController@destroy', $group])) !!}

  <div class="d-flex justify-content-end">
    <a class="btn btn-link mr-4" href="{{route('groups.show', $group)}}">{{ trans('messages.cancel') }}</a>
    {!! Form::submit(trans('messages.leave_this_group'), ['class' => 'btn btn-primary']) !!}
  </div>


  {!! Form::close() !!}

</div>

@endsection
