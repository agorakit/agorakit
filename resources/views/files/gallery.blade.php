@extends('app')

@section('content')

@include('partials.grouptab')


<div class="tab_content">

  <h2>{{trans('messages.files_in_this_group')}}

    @if ($upload_allowed)
    <a class="btn btn-primary btn-xs" href="{{ action('FileController@create', $group->id ) }}">
      <i class="fa fa-plus"></i>
      {{trans('messages.create_file_button')}}</a>
      @endif

    </h2>

    <p>
      <a class="btn btn-default btn-xs" href="{{ action('FileController@index', $group->id ) }}">
       <i class="fa fa-list "></i>
       {{trans('messages.show_list')}}</a>
    </p>


    <div class="gallery">

      @forelse( $files as $file )

      <div class="item">
        <a href="{{ action('FileController@show', [$group->id, $file->id]) }}"><img src="{{ action('FileController@preview', [$group->id, $file->id]) }}"/></a>
      </div>

      @empty
      {{trans('messages.nothing_yet')}}

      @endforelse
    </div>

    {!! $files->render() !!}

  </div>

  @endsection
