@extends('app')

@section('content')


@if(setting()->get('homepage_presentation_for_members') ||
setting()->localized()->get('homepage_presentation_for_members'))

<div class="overflow-y-auto border items-start  p-3 mb-4 rounded bg-gray-200 text-sm sm:text-base flex"
    style="max-height: 10em;">
    <i class="fa fa-info-circle text-4xl mr-3 text-gray-500 hidden sm:block"></i>
    <div class="text-gray-600 flex-grow -mb-3 mt-1">

        @if(Auth::user()->isAdmin())
        <a up-follow href="{{ url('/admin/settings') }}" class="btn btn-secondary float-right">
            <i class="fa fa-cog"></i>
            <span class="hidden sm:inline">{{__('Edit')}}</span>
        </a>
        @endif

        @if (setting()->localized()->get('homepage_presentation_for_members'))
        {!! setting()->localized()->get('homepage_presentation_for_members') !!}
        @else
        {!! setting()->get('homepage_presentation_for_members')!!}
        @endif

    </div>

</div>
@endif

@if (Auth::user()->groups()->count() > 0)

<div class="sm:flex justify-end hidden">
    @include('partials.preferences-show')
</div>



<div class="@if($actions->count() > 0) lg:grid lg:grid-cols-3 gap-32 @endif">

    <div class="col-span-2">
        <div class="my-5">
            <a class="btn btn-secondary" href="{{ route('discussions.create') }}">
                {{ trans('discussion.create_one_button') }}
            </a>
        </div>


        @if($discussions->count() > 0)
        <div class="">
            @foreach($discussions as $discussion)
            @include('discussions.discussion')
            @endforeach
        </div>
        @endif
    </div>

    <div class="col-span-1 mt-16 lg:mt-0">



        @if($actions->count() > 0)
        <div class="my-5">
            <a class="btn btn-secondary" href="{{ route('actions.create') }}">
                {{ trans('action.create_one_button') }}
            </a>
        </div>


        <div class="">
            @foreach($actions as $action)
            <x-action :action="$action" :participants="false" />
            @endforeach
        </div>
        @endif
    </div>

</div>

@else
<h1>
    {{trans('membership.join_your_first_group_title')}}
</h1>
<p>
    {{trans('membership.join_your_first_group_text')}}
</p>

<a up-target="body" class="btn btn-primary" href="{{ action('GroupController@index') }}">
    <i class="fa fa-layer-group"></i> {{ trans('messages.all_groups') }}
</a>
@endif


@endsection