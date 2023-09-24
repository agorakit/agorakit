@extends('app')

@section('content')
    <div class="mb-2">
        @include('dashboard.tabs')
    </div>

    <div class="mb-3">
        @include ('partials.preferences-show')
    </div>
    
    @include('partials.map')
@endsection
