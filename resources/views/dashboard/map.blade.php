@extends('app')

@section('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.0.1/dist/leaflet.js"></script>
    {!! Html::script('/packages/leafleticons/Leaflet.Icon.Glyph.js') !!}
@endsection

@section('css')
<style>
#map { height: 600px; }
</style>
@endsection

@section('content')

    <div class="page_header">
        <h1><a href="{{ action('DashboardController@index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.map') }} (EXPERIMENTAL)</h1>
        <p>{{trans('messages.map_info')}}</p>
    </div>

    <div class="tab_content">

        <div id="map"></div>

    </div>

    <script>
    // init map to a predefined center
    var map = L.map('map').setView([{{$latitude}}, {{$longitude}}],10);


    // voodoo magic to center it on everything if we have more than one user
    @if ($users->count() > 1)
    map.fitBounds([ @foreach($users as $user) [{{$user->latitude}}, {{$user->longitude}}], @endforeach ]);
    @endif

    // set provider and credits
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);


    // show all users
    @foreach($users as $user)
    L.marker([{{$user->latitude}}, {{$user->longitude}}], {
        icon: L.icon.glyph({
            prefix: 'fa',
            glyph: 'user'
        })
    })
    .bindPopup("<a href=\"{{action('UserController@show', $user)}}\">{{$user->name}}</a><br/>" + {!!json_encode(summary($user->body))!!} ).addTo(map);
    @endforeach


    // show all actions
    @foreach($actions as $action)
    L.marker([{{$action->latitude}}, {{$action->longitude}}], {
        icon: L.icon.glyph({
            prefix: 'fa',
            glyph: 'calendar-check-o'
        })
    })
    .bindPopup("<a href=\"{{action('ActionController@show', [$action->group, $action])}}\">{{$action->name}}</a><br/>" + {!!json_encode($action->body)!!} ).addTo(map);
    @endforeach


    // show all groups
    @foreach($groups as $group)
    L.marker([{{$group->latitude}}, {{$group->longitude}}], {
        icon: L.icon.glyph({
            prefix: 'fa',
            glyph: 'users'
        })
    })
    .bindPopup("<a href=\"{{action('GroupController@show', $group)}}\">{{$group->name}}</a><br/>" + {!!json_encode($group->body)!!} ).addTo(map);
    @endforeach


    </script>

    @endsection
