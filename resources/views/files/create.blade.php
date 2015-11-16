@extends('app')

@section('head')
{!! Html::style('/packages/dropzone/dropzone.css') !!}
@stop

@section('footer')
{!! Html::script('/packages/dropzone/dropzone.js') !!}
{!! Html::script('/packages/dropzone/dropzone-config.js') !!}
@stop




@section('content')

@include('partials.grouptab')


<div class="tab_content">

  <h1>Ajouter des fichiers</h1>

  <p>Glissez un ou plusieurs fichiers ci dessous</p>

  {!! Form::open(['url' => action('FileController@create', $group->id), 'class' => 'dropzone', 'files'=>true, 'id'=>'real-dropzone']) !!}

  <div class="fallback">
    <input name="file" type="file" multiple />
    <input type="submit"/>
  </div>

  {!! Form::close() !!}


</div>


@endsection
