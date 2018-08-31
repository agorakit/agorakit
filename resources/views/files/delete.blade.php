@extends('dialog')

@section('content')


  <h1>{{trans('messages.delete_confirm_title')}}</h1>



  <div class="small meta">

    <strong>{{$file->name}}</strong>

    <div>
      <a href="{{ route('groups.show', [$file->group]) }}">
        @if ($file->group->isOpen())
          <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
        @elseif ($file->group->isClosed())
          <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
        @else
          <i class="fa fa-eye-slash" title="{{trans('group.secret')}}"></i>
        @endif
        {{ $file->group->name }}
      </a>
    </div>

    <div>
      <a href="{{ route('users.show', [$file->user]) }}">
        <i class="fa fa-user-circle"></i> {{ $file->user->name }}
      </a>
    </div>

    <div>
      <i class="fa fa-clock-o"></i> {{ $file->updated_at }}
    </div>

    <div>
      @if ($file->isFile())
        <i class="fa fa-database"></i> {{sizeForHumans($file->filesize)}}
      @endif
    </div>
  </div>

  {!! Form::model($file, array('method' => 'DELETE', 'action' => ['GroupFileController@destroy', $group, $file])) !!}



  <div class="mt-5 d-flex justify-content-between align-items-center">
    {!! Form::submit(trans('messages.delete_confirm_button'), ['class' => 'btn btn-danger']) !!}
  </div>


  {!! Form::close() !!}



@endsection
