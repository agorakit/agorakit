@extends('app')

@section('content')

    <div class="page_header">
        <h1><i class="fa fa-home"></i>
            {{Config::get('mobilizator.name')}}
        </h1>
    </div>

    @include('dashboard.tabs')


    <div class="tab_content">

        {!! setting('homepage_presentation', trans('documentation.intro')) !!}

        @if (Auth::check())
            <a class="btn btn-primary btn-xs" href="{{action('SettingsController@settings')}}">
                <i class="fa fa-pencil"></i> {{trans('messages.modify_intro_text')}}
            </a>
        @endif
    
    </div>

@endsection
