@extends('app')


@section('content')

  @include('partials.grouptab')


  <div class="tab_content">

    <h1>{{trans('messages.create_folder_button')}}</h1>




    {!! Form::open(['url' => action('FileController@storeFolder', $group->id)]) !!}


    @if ($parent)
        <input name="parent_id" type="hidden" value="{{$parent->id}}" />
    @endif

    <input name="folder" type="text"/>
    <div class="form-group">
      {!! Form::submit(trans('group.create_button'), ['class' => 'btn btn-primary form-control']) !!}
    </div>


    {!! Form::close() !!}


  </div>


@endsection
