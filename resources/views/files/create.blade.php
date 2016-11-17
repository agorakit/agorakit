@extends('app')

@section('css')
{!! Html::style('/packages/dropzone/dropzone.css') !!}
@stop

@section('js')
{!! Html::script('/packages/dropzone/dropzone.js') !!}
{!! Html::script('/packages/dropzone/dropzone-config.js') !!}
@stop




@section('content')

@include('partials.grouptab')


<div class="tab_content">

  <h1>{{trans('messages.create_file_button')}}</h1>

  <p>{{trans('messages.drop_file_here')}}
      </p>

  {!! Form::open(['url' => action('FileController@create', [$group->id, $parent]), 'class' => 'dropzone', 'files'=>true, 'id'=>'real-dropzone']) !!}

  <!--
  @if ($parent)
      <input name="parent_id" type="hidden" value="{{$parent->id}}" />
  @endif
-->

    <input name="file" type="file" multiple />
    <input type="submit"/>


  {!! Form::close() !!}


</div>


@endsection
