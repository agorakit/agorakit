@extends('app')


@section('content')
    @include('groups.tabs')
    <div class="tab_content">
        <div class="spacer"></div>

        @include('partials.map')


    </div>
@endsection
