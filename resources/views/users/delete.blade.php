@extends('dialog')

@section('content')

  <h1>Delete "{{ $user->name }}" ({{ $user->email }}) and all the related content ?</h1>

  <div class="alert alert-info">
    <ul>
      <li>All your comments and files will be deleted. </li>
      <li>Discussions you started will be assigned to anonymous user if there are replies from other users</li>
      <li>You will be removed from all groups, but groups you created won't be deleted. Please first delete the groups you created if needed</li>
    </ul>
  </div>

  <div class="alert alert-warning">Please note that undoing this will be impossible after some time, and will require admin work</div>

  <h2>Choose what to do with the content :</h2>

  {!! Form::open(['route' => ['users.delete', $user], 'method'=>'delete'])!!}



  <div class="form-check">
    <input class="form-check-input" type="radio" name="content" value="anonymous" checked>
    <label class="form-check-label" for="exampleRadios1">
      Assign the content to the anonymous user
    </label>
  </div>
  <div class="form-check">
    <input class="form-check-input" type="radio" name="content" value="delete">
    <label class="form-check-label" for="exampleRadios2">
      Delete all the content
    </label>
  </div>

  <div class="mt-5 d-flex justify-content-between align-items-center">
    <a class="btn btn-link mr-4" href="{{route('users.show', $user)}}">{{ trans('messages.cancel') }}</a>
    {!! Form::submit('Delete my account  (remember, no easy undo!)', ['class' => 'btn btn-danger']) !!}
  </div>

  {!! Form::close() !!}


@endsection
