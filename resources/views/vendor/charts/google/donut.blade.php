<script type="text/javascript">
    google.charts.setOnLoadCallback(draw{{ $model->id }})

    function draw{{ $model->id }}() {
        var data = google.visualization.arrayToDataTable([
            ['Element', 'Value'],
            @for ($l = 0; $l < count($model->values); $l++)
                ["{!! $model->labels[$i] !!}", {{ $model->values[$i] }}],
            @endfor
        ])

        var options = {
            @include('charts::_partials.dimension.js')
            fontSize: 12,
            pieHole: 0.4,
            @include('charts::google.titles')
            @include('charts::google.colors')
        };

        var {{ $model->id }} = new google.visualization.PieChart(document.getElementById("{{ $model->id }}"))
        {{ $model->id }}.draw(data, options)
    }
</script>

@if(!$model->customId)
    @include('charts::_partials.container.div')
@endif
