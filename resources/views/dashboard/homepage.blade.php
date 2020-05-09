@extends('app')

@section('content')


    <div class="d-flex justify-content-between mt-2 mb-2">
        <div class="">
            <h1><a up-follow href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ __('Homepage') }}</h1>
        </div>

        <div class="">
            @include ('partials.preferences-show')
        </div>
    </div>



    <div class="row">
        <div class="col-md-7">


            @if ($discussions->count() > 0)
                <h2 class="d-flex justify-content-between mb-3"> {{ __('Latest discussions') }}
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('discussions.create') }}">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                </h2>


                <div class="discussions">
                    @forelse( $discussions as $discussion )
                        @include('discussions.discussion')
                    @empty
                        {{trans('messages.nothing_yet')}}
                    @endforelse
                </div>

            @endif
        </div>


        <div class="col-md-5">

            @if (setting('homepage_presentation_for_members', false))
                <div class="alert alert-secondary" style="max-height: 15em; overflow-y: scroll; overflow-x:hidden">
                    {!! setting('homepage_presentation_for_members') !!}
                </div>
            @endif

            @if ($actions->count() > 0)
                <h2 class="d-flex justify-content-between mb-3">{{ __('Calendar') }}
                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('actions.create') }}">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                </h2>


                <div class="actions">
                    @forelse( $actions as $action)
                        @include('actions.action')
                    @empty
                        {{trans('messages.nothing_yet')}}
                    @endforelse
                </div>
            @endif

            @if ($files->count() > 0)
                <h2>{{ __('Files') }}</h2>

                <div class="files">
                    @forelse( $files as $file)
                        @include('files.file-simple')
                    @empty
                        {{trans('messages.nothing_yet')}}
                    @endforelse
                </div>
            @endif
        </div>
    </div>









@endsection
