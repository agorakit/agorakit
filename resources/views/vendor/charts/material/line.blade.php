<script type="text/javascript">
    google.charts.load('current', {'packages':['bar']})

    google.charts.setOnLoadCallback(draw{{ $model->id }})
        function draw{{ $model->id }}() {
        var data = google.visualization.arrayToDataTable([
            ['', "{!! $model->element_label !!}"],
            @for($i = 0; $i < count($model->values); $i++)
                ["{!! $model->labels[$i] !!}", {{ $model->values[$i] }}],
            @endfor
        ])

        var options = {
            chart: {
              @if($model->title)
                title: "{!! $model->title !!}",
              @endif
            },
            @if($model->colors)
                colors: ["{{ $model->colors[0] }}"],
            @endif
        };

        var {{ $model->id }} = new google.charts.Line(document.getElementById("{{ $model->id }}"))

        {{ $model->id }}.draw(data, options)
    }
</script>

@include('charts::_partials.container.div')
