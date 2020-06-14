@extends('app')

@section('head')
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
  integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
  crossorigin=""/>
  <!-- Make sure you put this AFTER Leaflet's CSS -->
  <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
  integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
  crossorigin=""></script>
@endsection

@section('css')
  <style>
  #mapid { height: 600px; }
</style>
@endsection

@section('js')

  <script>

  // load all our points using geojson
  // taken from https://medium.com/@maptastik/loading-external-geojson-a-nother-way-to-do-it-with-jquery-c72ae3b41c01
  var points = $.ajax({
    url:"{{ route('map.geojson') }}",
    dataType: "json",
    success: console.log("points data successfully loaded."),
    error: function (xhr) {
      alert(xhr.statusText)
    }
  })


  // When loading is complete :
  $.when(points).done(function() {
    var map = L.map('mapid').setView([51.505, -0.09], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add requested external GeoJSON to map
    var kyCounties = L.geoJSON(points.responseJSON).addTo(map);

    // todo get lat and long fonr the json
    var bounds = new L.LatLngBounds(points.responseJSON);

    map.fitBounds(bounds);

  });




</script>
@endsection

@section('content')


  <div class="toolbox d-md-flex">
    <div class="d-flex mb-2">
      <h1>
        <a up-follow href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.map') }}
      </hi>
    </div>

    <div class="ml-auto">
      @include ('partials.preferences-show')
    </div>
  </div>


  <p class="help">{{trans('messages.map_info')}}</p>



  <div id="mapid"></div>



@endsection
