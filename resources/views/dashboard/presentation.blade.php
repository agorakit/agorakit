@extends('app')

@section('content')

    <div class="tab_content">

        <h1>
            {{setting('name')}}
        </h1>

        <div class="mb-3">
            @if (setting()->localized()->get('homepage_presentation'))
            {!! setting()->localized()->get('homepage_presentation') !!}
            @else
            {!! setting()->get('homepage_presentation', trans('documentation.intro'))!!}
            @endif
        </div>

        <a up-modal=".dialog" class="btn btn-primary btn-lg mr-2" href="{{ url('login') }}">{{ trans('messages.login') }}</a>
        
        @can('create', App\User::class)
            <a up-modal=".dialog" class="btn btn-secondary btn-lg" href="{{ url('register') }}">
                {{ trans('messages.register') }}
            </a>
        @endcan


    </div>

@endsection
