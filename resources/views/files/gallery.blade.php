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

  @section('footer')

  <style>
  .item {
    float: left;
    width: 250px;
  }

  .item img
  {
    width: 100%;
    height: auto;
  }
  </style>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/packery/1.4.3/packery.pkgd.js"></script>
  <script>
  var $container = $('.gallery');
  // init
  $container.packery({
    itemSelector: '.item',
    gutter: 10
  });

$(document).ready(function() {
  $container.packery();
});

  </script>



  @endsection
