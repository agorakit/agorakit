@extends('group')

@section('content')

    @auth
        <div class="flex mb-4 justify-content-between">

            <div class="mb-2">
                <div class="btn-group" role="group">

                    <a class="btn @if ($type == 'list') btn-outline-primary @else btn-primary @endif" href="?type=grid" up-follow><i class="fa fa-calendar"></i>
                        {{ trans('messages.grid') }}</a>
                    <a class="btn @if ($type == 'grid') btn-outline-primary @else btn-primary @endif" href="?type=list" up-follow><i class="fa fa-list"></i>
                        {{ trans('messages.list') }}</a>
                </div>
            </div>

            @can('create-action', $group)
                <div>
                    <a class="btn btn-primary" href="{{ route('groups.actions.create', $group) }}">
                        <i class="fas fa-pencil-alt"></i>
                        <span class="hidden sm:inline ml-2">{{ trans('action.create_one_button') }}</span>
                    </a>
                </div>
            @endcan

        </div>
    @endauth

    @if ($type == 'grid')
        <div class="calendar" id="calendar" data-json="{{ route('groups.actions.index.json', $group) }}" data-locale="{{ App::getLocale() }}"
            data-create-url="{{ route('groups.actions.create', $group) }}"></div>

        @include('actions.ical')
    @endif

    @if ($type == 'list')
        @if ($actions->count() > 0)
            <div class="actions">
                @forelse($actions as $action)
                    <x-action :action="$action" :participants="true" />
                @empty
                    {{ trans('messages.nothing_yet') }}
                @endforelse
            </div>

            {{ $actions->render() }}

            @include('actions.ical')
        @endif
    @endif

@endsection
