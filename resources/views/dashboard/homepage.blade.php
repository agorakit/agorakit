@extends('app')

@section('content')

    @if (setting()->get('homepage_presentation_for_members') ||
            setting()->localized()->get('homepage_presentation_for_members'))
        <div class="overflow-y-auto border items-start  p-3 mb-4 rounded bg-gray-200 text-sm sm:text-base flex"
            style="max-height: 10em;">
            <div class="">

                @if (Auth::user()->isAdmin())
                    <a class="btn btn-secondary float-end" href="{{ url('/admin/settings') }}" >
                        <i class="fa fa-cog me-2"></i>
                        {{ __('Edit') }}
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
            <div class="col-lg-8 col-12">
                @if ($discussions->count() > 0)
                    @if ($discussions)
                        @include('discussions.list', ['discussions' => $discussions])
                    @endif
                @endif
            </div>

            @if ($actions->count() > 0)
                <div class="col-lg-4 col-12">
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
