@extends('app')

@section('content')


        <div style="float:right">
            <a class="btn btn-primary" href="{{ route('actions.create') }}">
                <i class="fa fa-plus"></i> {{trans('action.create_one_button')}}
            </a>
        </div>

        <h1>
            <a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.agenda') }}
        </h1>



    <div class="btn-group mt-3 mb-4" role="group">
      <a href="?type=grid" class="btn btn-primary"><i class="fa fa-calendar"></i> {{trans('messages.grid')}}</a>
      <a href="?type=list" class="btn btn-primary disabled"><i class="fa fa-list"></i> {{trans('messages.list')}}</a>
    </div>

    <div class="tab_content">



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


        <p><a href="{{action('IcalController@index')}}">{{trans('messages.download_ical')}}</a></p>

    </div>

@endsection
