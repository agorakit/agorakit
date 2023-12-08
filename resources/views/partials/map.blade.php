<link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<style>
    #mapid {
        height: 600px;
    }

    .marker {
        width: 1rem;
        height: 1rem;
        display: block;
        position: relative;
        border-radius: 50%;
        border: 2px solid gray;
        box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.5);
    }
</style>

<script defer>
    $(document).ready(function() {
        // load all our points using geojson
        // taken from https://medium.com/@maptastik/loading-external-geojson-a-nother-way-to-do-it-with-jquery-c72ae3b41c01
        var points = $.ajax({
            url: "{{ $json }}",
            dataType: "json",
            success: console.log("points data successfully loaded."),
            error: function(xhr) {
                alert(xhr.statusText)
            }
        })


        // When loading is complete :
        $.when(points).done(function() {
            var map = L.map('mapid').setView([51.505, -0.09], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);





            // from https://stackoverflow.com/questions/23567203/leaflet-changing-marker-color

            const userIcon = L.divIcon({
                className: "my-custom-pin",
                iconAnchor: [0, 24],
                labelAnchor: [-6, 0],
                popupAnchor: [0, -36],
                html: `<span style="background-color: #1e60c9" class="marker"></span>`
            })

            const actionIcon = L.divIcon({
                className: "my-custom-pin",
                iconAnchor: [0, 24],
                labelAnchor: [-6, 0],
                popupAnchor: [0, -36],
                html: `<span style="background-color: #871ec9" class="marker"></span>`
            })

            const groupIcon = L.divIcon({
                className: "my-custom-pin",
                iconAnchor: [0, 24],
                labelAnchor: [-6, 0],
                popupAnchor: [0, -36],
                html: `<span style="background-color: #8dc91e" class="marker"></span>`
            })

            // from https://gist.github.com/geog4046instructor/80ee78db60862ede74eacba220809b64
            // replace Leaflet's default blue marker with a custom icon
            function createCustomIcon(feature, latlng) {
                if (feature.properties.type == 'user') {
                    return L.marker(latlng, {
                        icon: userIcon
                    })
                }

                if (feature.properties.type == 'action') {
                    return L.marker(latlng, {
                        icon: actionIcon
                    })
                }

                if (feature.properties.type == 'group') {
                    return L.marker(latlng, {
                        icon: groupIcon
                    })
                }

                return L.marker(latlng, {
                    icon: userIcon
                })
            }

            // Add requested external GeoJSON to map
            var allPoints = L.geoJSON(points.responseJSON, {
                    pointToLayer: createCustomIcon
                })
                .bindPopup(function(layer) {
                    return "<strong>" + layer.feature.properties.title + '</strong><br/>' + layer.feature
                        .properties.description;
                }).addTo(map);



            map.fitBounds(allPoints.getBounds());

        });

    });
</script>

<div class="mb-4" id="mapid"></div>

<span class="badge" style="background-color: #8dc91e;">Group</span>

<span class="badge" style="background-color: #1e60c9">User</span>

<span class="badge" style="background-color: #871ec9">Action</span>
