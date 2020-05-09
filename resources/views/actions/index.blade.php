@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        @auth
            <div class="toolbox d-md-flex mb-4">
                <div class="mb-2">
                    <div class="btn-group" role="group">
                        <a up-follow href="?type=grid" class="btn btn-primary"><i class="fa fa-calendar"></i> {{trans('messages.grid')}}</a>
                        <a up-follow href="?type=list" class="btn btn-outline-primary"><i class="fa fa-list"></i> {{trans('messages.list')}}</a>
                    </div>
                </div>

                @can('create-action', $group)
                    <div class="ml-auto">
                        <a class="btn btn-primary" href="{{ route('groups.actions.create', $group ) }}">
                            <i class="fa fa-plus"></i> {{trans('action.create_one_button')}}
                        </a>
                    </div>
                @endcan

            </div>
        @endauth


        <div id="calendar" class="calendar" data-json="{{route('groups.actions.index.json', $group)}}" data-locale="{{App::getLocale()}}"></div>

        @include('actions.ical')


    </div>

@endsection
