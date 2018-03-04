@extends('app')

@section('content')

  @include('groups.tabs')

  {!!$group->getSetting('module_custom_html') !!}


@endsection
