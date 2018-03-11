@extends('app')


@section('content')

    @include('groups.tabs')

    <div class="tab_content">



        @can('create-action', $group)
            <div class="toolbox"  style="float:right">
                <a class="btn btn-primary" href="{{ route('groups.actions.create', $group->id ) }}">
                    <i class="fa fa-plus"></i> {{trans('action.create_one_button')}}
                </a>
            </div>
        @endcan


            <div class="btn-group mt-3 mb-4" role="group">
              <a href="?type=grid" class="btn btn-primary"><i class="fa fa-calendar"></i> {{trans('messages.grid')}}</a>
              <a href="?type=list" class="btn btn-primary disabled"><i class="fa fa-list"></i> {{trans('messages.list')}}</a>
            </div>

        @if ($actions->count() > 0)
            <div class="actions">
                @forelse( $actions as $action)
                    @include('actions.action')
                @empty
                    {{trans('messages.nothing_yet')}}
                @endforelse
            </div>

            {{$actions->render()}}
        @endif


        <p><a href="{{action('IcalController@group', $group->id)}}">Téléchargez le calendrier de ce groupe au format iCal</a></p>

@endsection
