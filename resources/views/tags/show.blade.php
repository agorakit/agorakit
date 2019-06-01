@extends('app')

@section('content')

  @include('groups.tabs')
  <div class="tab_content">

    {{$tag->name}}

  </div>

@endsection
