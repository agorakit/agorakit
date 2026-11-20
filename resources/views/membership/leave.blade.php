@extends('dialog')

@section('content')


  <h1>{{ t('Leaving the group called') }} <em>{{$group->name}}</em> ?</h1>


  <p>
    {{ t('We are sad to see you go... confirm that you want to leave this group. You won\'t receive notifications anymore.') }}
  </p>



  {!! Form::open(array('action' => ['GroupMembershipController@destroy', $group])) !!}

  <div class="d-flex justify-content-end">
    <a class="btn btn-link mr-4" href="{{route('groups.show', $group)}}">{{ t('Cancel') }}</a>
    {!! Form::submit(t('Leave this group'), ['class' => 'btn btn-primary']) !!}
  </div>


  {!! Form::close() !!}

</div>

@endsection
