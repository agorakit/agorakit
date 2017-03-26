@extends('app')


@section('content')

  @include('groups.tabs')


  <div class="tab_content">

    <h1>{{trans('messages.create_folder')}}</h1>




    {!! Form::open(['url' => action('FileController@storeFolder', $group)]) !!}

    @if ($parent)
      <input name="parent_id" type="hidden" value="{{$parent->id}}" />
    @endif

    <div class="form-group">
      <input name="folder" type="text"/>
    </div>
    
    <div class="form-group">
      {!! Form::submit(trans('group.create'), ['class' => 'btn btn-primary form-control']) !!}
    </div>


    {!! Form::close() !!}


  </div>


@endsection
