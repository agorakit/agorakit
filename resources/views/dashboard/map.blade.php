@extends('app')

@section('content')
    <div class="mb-2">
        @include('dashboard.tabs')
    </div>
    @include('partials.map')
@endsection
