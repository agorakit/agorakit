@extends('app')

@section('content')
    {!! filter($group->getSetting('module_custom_html')) !!}
@endsection
