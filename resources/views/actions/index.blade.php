@extends('group')

@section('content')

    @auth
        <div class="flex mb-2 justify-content-between">

            <div class="mb-2">
                <div class="btn-group" role="group">

                    <a class="btn @if ($type == 'list') btn-outline-primary @else btn-primary @endif"
                        href="?type=grid"><i class="fa fa-calendar me-2"></i>
                        {{ trans('messages.grid') }}</a>
                    <a class="btn @if ($type == 'grid') btn-outline-primary @else btn-primary @endif"
                        href="?type=list"><i class="fa fa-list me-2"></i>
                        {{ trans('messages.list') }}</a>
                </div>
            </div>

        </div>
    @endauth

    @can('create-action', $group)
        <div class="mb-4">
            <a class="btn btn-primary" href="{{ route('groups.actions.create', $group) }}">
                {{ trans('action.create_one_button') }}
            </a>
        </div>
    @endcan

    @if ($type == 'grid')
        <div class="js-calendar" id="calendar" data-json="{{ route('groups.actions.index.json', $group) }}"
            data-locale="{{ App::getLocale() }}" data-create-url="{{ route('groups.actions.create', $group) }}"></div>

        @include('actions.ical')
    @endif

    @if ($type == 'list')
        @if ($actions->count() > 0)
            @include('actions.list', ['actions' => $actions])

            {{ $actions->render() }}

            @include('actions.ical')
        @endif
    @endif

@endsection
