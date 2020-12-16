@extends('app')

@section('content')

    <div class="tab_content">

        <h1>
            {{setting('name')}}
        </h1>

        <div class="mb-3">
            {!! setting('homepage_presentation', trans('documentation.intro')) !!}
        </div>

        <a up-modal=".dialog" class="btn btn-primary btn-lg mr-2" href="{{ url('login') }}">{{ trans('messages.login') }}</a>
        
        @can('create', App\User::class)
            <a up-modal=".dialog" class="btn btn-secondary btn-lg" href="{{ url('register') }}">
                {{ trans('messages.register') }}
            </a>
        @endcan


    </div>

@endsection
