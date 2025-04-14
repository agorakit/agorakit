@extends('app')

@section('content')
    {!! $group->getSetting('module_custom_html') !!}
@endsection
