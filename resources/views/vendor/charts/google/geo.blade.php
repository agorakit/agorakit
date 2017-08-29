<script type="text/javascript">
    google.charts.setOnLoadCallback(draw{{ $model->id }})

    function draw{{ $model->id }}() {
        var data = google.visualization.arrayToDataTable([
            ['Country', "{!! $model->element_label !!}"],
            @for ($i = 0; $i < count($model->values); $i++)
                ["{{ $model->labels[$i] }}", {{ $model->values[$i] }}],
            @endfor
        ])

        var options = {
            @include('charts::_partials.dimension.js')
            colorAxis: {
                colors: [
                    @if($model->colors and count($model->colors >= 2))
                        "{{ $model->colors[0] }}", "{{ $model->colors[1] }}"
                    @endif
                ]
            },
            region: "{{ $model->region ? $model->region : 'world' }}",
            datalessRegionColor: "#e0e0e0",
            defaultColor: "#607D8",
        };

        var {{ $model->id }} = new google.visualization.GeoChart(document.getElementById("{{ $model->id }}"))

        {{ $model->id }}.draw(data, options)
    }
</script>

@if(!$model->customId)
    @include('charts::_partials/container.div-titled')
@endif
