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
  .loadURL('map.geojson')
  .on('ready', function() {
    map.fitBounds(myLayer.getBounds());
  })
  .addTo(map);

</script>
@endsection

@section('content')


  <div class="toolbox d-md-flex">
    <div class="d-flex mb-2">
      <h1>
        <a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.map') }}
      </hi>
    </div>

    <div class="ml-auto">
      @include ('partials.preferences-show')
    </div>
  </div>


  <p class="help">{{trans('messages.map_info')}}</p>



  <div id="map"></div>



@endsection
