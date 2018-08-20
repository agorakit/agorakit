@extends('app')

@section('head')
  <script src='https://api.mapbox.com/mapbox.js/v3.1.1/mapbox.js'></script>
  <link href='https://api.mapbox.com/mapbox.js/v3.1.1/mapbox.css' rel='stylesheet' />
@endsection

@section('css')
  <style>
  #map { height: 600px; }
</style>
@endsection

@section('js')

  <script>

  L.mapbox.accessToken = '{{ config('map.mapbox_token', 'set_your_access_token_in_dot_env') }}';
  var map = L.mapbox.map('map', 'mapbox.streets');

  var myLayer = L.mapbox.featureLayer()
  .loadURL('{{route('groups.map.geojson', $group)}}')
  .on('ready', function() {
    map.fitBounds(myLayer.getBounds());
  })
  .addTo(map);

</script>
@endsection


@section('content')
  @include('groups.tabs')
  <div class="tab_content">
    <div class="spacer"></div>
    <div id="map"></div>
  </div>
@endsection
