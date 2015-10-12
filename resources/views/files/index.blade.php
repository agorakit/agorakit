@extends('app')

@section('head')
    {!! HTML::style('/packages/dropzone/dropzone.css') !!}
@stop

@section('footer')
    {!! HTML::script('/packages/dropzone/dropzone.js') !!}
    {!! HTML::script('/js/dropzone-config.js') !!}
@stop


@section('content')

@include('partials.grouptab')

<div class="container">
  <h2>All the files in this group</h2>



    <table class="table table-hover">
      @foreach( $files as $file )
      <tr>

        <td>
          <a href="{{ action('FileController@show', [$group->id, $file->id]) }}"><img src="{{ action('FileController@thumbnail', [$group->id, $file->id]) }}"/></a>
        </td>

        <td>
          <a href="{{ action('FileController@show', [$group->id, $file->id]) }}">{{ $file->name }}</a>
        </td>

        <td>
          <a href="{{ action('FileController@show', [$group->id, $file->id]) }}">Download</a>
        </td>

        <td>
          @unless (is_null ($file->user))
          <a href="{{ action('UserController@show', $file->user->id) }}">{{ $file->user->name }}</a>
          @endunless
        </td>

        <td>
          {{ $file->created_at->diffForHumans() }}
        </td>
      </tr>
      @endforeach
    </table>

    {!! $files->render() !!}

  </div>

<div class="container">

                  {!! Form::open(['url' => action('FileController@create', $group->id), 'class' => 'dropzone', 'files'=>true, 'id'=>'real-dropzone']) !!}

                  <div class="fallback">
                      <input name="file" type="file" multiple />
                      <input type="submit"/>
                  </div>



                  {!! Form::close() !!}

</div>








  @endsection
