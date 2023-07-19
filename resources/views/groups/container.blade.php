@extends('app')

@section('content')
    @include('groups.tabs')
    <div class="container text-bg-light p-4">
        @yield('group')
    </div>
@endsection
