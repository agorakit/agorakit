@extends('app')

@section('content')

    @if (setting()->get('homepage_presentation_for_members') ||
            setting()->localized()->get('homepage_presentation_for_members'))
        <div class="overflow-y-auto border items-start  p-3 mb-4 rounded bg-gray-200 text-sm sm:text-base flex"
            style="max-height: 10em;">
            <i class="fa fa-info-circle text-4xl mr-3 text-gray-500 hidden sm:block"></i>
            <div class="text-secondary flex-grow -mb-3 mt-1">

                @if (Auth::user()->isAdmin())
                    <a class="btn btn-secondary float-right" href="{{ url('/admin/settings') }}" up-follow>
                        <i class="fa fa-cog"></i>
                        <span class="hidden sm:inline">{{ __('Edit') }}</span>
                    </a>
                @endif

                @if (setting()->localized()->get('homepage_presentation_for_members'))
                    {!! setting()->localized()->get('homepage_presentation_for_members') !!}
                @else
                    {!! setting()->get('homepage_presentation_for_members') !!}
                @endif

            </div>

        </div>
    @endif

    @if (Auth::user()->groups()->count() > 0)
        <div class="row">

            <div class="col">
                @if ($discussions->count() > 0)
                    @if ($discussions)
                        @include('discussions.list', ['discussions' => $discussions])
                    @endif
                @endif
            </div>

            @if ($actions->count() > 0)
                <div class="col">
                    @include('actions.list', ['actions' => $actions])
                </div>
            @endif

        </div>
    @else
        <h1>
            {{ trans('membership.join_your_first_group_title') }}
        </h1>
        <p>
            {{ trans('membership.join_your_first_group_text') }}
        </p>

        <a class="btn btn-primary" href="{{ action('GroupController@index') }}" up-target="body">
            <i class="fa fa-layer-group"></i> {{ trans('messages.all_groups') }}
        </a>
    @endif

@endsection
