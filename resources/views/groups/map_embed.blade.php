@extends('embed')

@section('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.0.1/dist/leaflet.js"></script>
    {!! Html::script('/packages/leafleticons/Leaflet.Icon.Glyph.js') !!}
@endsection

@section('css')
<style>


html, body, #map
{
    height: 100%;
}

</style>

@endsection

@section('content')


    <div id="map"></div>



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
    .bindPopup("<a href=\"{{route('users.show', $user)}}\">{{$user->name}}</a><br/>" + {!!json_encode(summary($user->body))!!} ).addTo(map);
    @endforeach


    // show all actions
    @foreach($actions as $action)
    L.marker([{{$action->latitude}}, {{$action->longitude}}], {
        icon: L.icon.glyph({
            prefix: 'fa',
            glyph: 'calendar-check-o'
        })
    })
    .bindPopup("<a href=\"{{route('groups.actions.show', [$action->group, $action])}}\">{{$action->name}}</a><br/>" + {!!json_encode($action->body)!!} ).addTo(map);
    @endforeach

    </script>

    @endsection
