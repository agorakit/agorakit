@extends('groups.container')

@section('group')
    {!! $group->getSetting('module_custom_html') !!}
@endsection
