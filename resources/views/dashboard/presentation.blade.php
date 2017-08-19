@extends('app')

@section('content')

    <div class="page_header">
        <h1><i class="fa fa-home"></i>
            {{Config::get('agorakit.name')}}
        </h1>
    </div>

    @include('dashboard.tabs')


    <div class="tab_content">

        {!! setting('homepage_presentation', trans('documentation.intro')) !!}
        
    </div>

@endsection
