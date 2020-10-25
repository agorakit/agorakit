@extends('app')

@section('content')

@include('groups.tabs')

<div class="tab_content">

    @auth
        <div class="flex mb-4 justify-between">

            <div class="mb-2">
                <div class="btn-group" role="group">
                
                    <a up-follow href="?type=grid" class="btn @if($type == 'list') btn-outline-primary @else btn-primary @endif"><i class="fa fa-calendar"></i>
                        {{ trans('messages.grid') }}</a>
                    <a up-follow href="?type=list" class="btn @if($type == 'grid') btn-outline-primary @else btn-primary @endif"><i class="fa fa-list"></i>
                        {{ trans('messages.list') }}</a>
                </div>
            </div>

            @can('create-action', $group)
                
                  <a up-follow class="bg-gray-700 text-gray-100 rounded-full shadow-md text-sm h-10 px-4 flex items-center"
            href="{{ route('groups.actions.create', $group ) }}">
            <i class="fas fa-pencil-alt"></i>
            <span class="hidden sm:inline ml-2">{{ trans('action.create_one_button') }}</span>
        </a>

                
            @endcan

        </div>
    @endauth


    @if($type == 'grid')

        <div id="calendar" class="calendar"
            data-json="{{ route('groups.actions.index.json', $group) }}"
            data-locale="{{ App::getLocale() }}"
            data-create-url="{{ route('groups.actions.create', $group) }}"></div>

        @include('actions.ical')
    @endif

    @if($type == 'list')

    

        @if($actions->count() > 0)
            <div class="actions">
                @forelse( $actions as $action)
                    @include('actions.action')
                @empty
                    {{ trans('messages.nothing_yet') }}
                @endforelse
            </div>

            {{ $actions->render() }}

            @include('actions.ical')
        @endif
    @endif



</div>

@endsection