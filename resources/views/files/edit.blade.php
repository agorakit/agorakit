@extends('app')

@section('content')

  @include('partials.grouptab')
  <div class="tab_content">
    <h1>Tag</h1>


    {!! Form::model($file, ['action' => ['FileController@update', $file->group->id, $file->id], 'files' => true]) !!}



    <div class="form-group">
        {!! Form::label('tags[]', 'Tag(s)') !!}
        {!! Form::select('tags[]', \App\File::existingTags()->pluck('name', 'slug'), $file->tags->pluck('name', 'slug'),['class' =>'form-control input-lg','multiple'=>true,'id' => 'tags']) !!}
    </div>


    <div class="form-group">
      {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
    </div>


    {!! Form::close() !!}


  </div>

@endsection

@section('footer')
  <script type="text/javascript">
  $('#tags').select2(
    {
      tags: true
    }
  );
  </script>
@endsection
