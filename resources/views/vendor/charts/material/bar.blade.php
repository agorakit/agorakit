<script type="text/javascript">
    google.charts.load('current', {'packages':['bar']})

    google.charts.setOnLoadCallback(draw{{ $model->id }})

    function draw{{ $model->id }}() {
        var data = google.visualization.arrayToDataTable([
            [
                '', "{!! $model->element_label !!}",
                @if($model->colors)
                    { role: 'style' }
                @endif
            ],
                @for($i = 0; $i < count($model->values); $i++)
                    ["{!! $model->labels[$i] !!}", {{ $model->values[$i] }},"{{ $model->colors[$i] }}"],
                @endfor
        ])

        var options = {
            chart: {
                @if($model->title)
                    title: "{!! $model->title !!}",
                @endif
            },
            @if($model->colors)
                colors:[
                    @foreach($model->colors as $color)
                        "{{ $color}}",
                    @endforeach
                ],
            @endif
        };

        var {{ $model->id }} = new google.charts.Bar(document.getElementById("{{ $model->id }}"))
        {{ $model->id }}.draw(data, options)
    }
</script>

@include('charts::_partials.container.div')
