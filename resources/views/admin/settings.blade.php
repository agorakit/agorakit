@extends('app')

@section('content')



  {!! Form::open(array('action' => ['AdminController@update'])) !!}

  <div class="form-group">
    {!! Form::textarea('homepage_presentation', $homepage_presentation, ['id' => 'wysiwyg', 'class' => 'form-control' , 'required']) !!}
  </div>

  @include('partials.wysiwyg')

  <div class="form-group">
    {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
