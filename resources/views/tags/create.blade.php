@extends('app')

@section('content')

  @include('groups.tabs')


  <div class="tab_content">

    <h1>{{trans('tag.create_one_button')}}</h1>

    {!! Form::open(array('action' => ['GroupTagController@store', $group])) !!}

    @include('tags.form')

    <div class="form-group">
      {!! Form::submit(trans('messages.create'), ['class' => 'btn btn-primary form-control']) !!}
    </div>

    {!! Form::close() !!}

  </div>

@endsection
