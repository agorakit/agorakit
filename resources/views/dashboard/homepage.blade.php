@extends('app')

@section('content')


    <div class="d-flex justify-content-between">
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
                <h2> {{ __('Latest discussions') }}</h2>
                <a class="badge badge-pill badge-primary mb-4" href="{{ route('discussions.create') }}">
                    <i class="fa fa-plus"></i> {{trans('discussion.create_one_button')}}
                </a>


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
                <h2>{{ __('Calendar') }}</h2>
                <a class="badge badge-pill badge-primary mb-4" href="{{ route('actions.create') }}">
                    <i class="fa fa-plus"></i> {{trans('action.create_one_button')}}
                </a>

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
