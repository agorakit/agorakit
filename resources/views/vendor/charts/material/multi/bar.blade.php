<script type="text/javascript">
    google.charts.load('current', {'packages':['bar']})

    google.charts.setOnLoadCallback(draw{{ $model->id }})

    function draw{{ $model->id }}() {
        var data = google.visualization.arrayToDataTable([
            [
                '',
                @for ($i = 0; $i < count($model->datasets); $i++)
                    "{{ $model->datasets[$i]['label'] }}",
                @endfor
            ],

            @for ($l = 0; $l < count($model->labels); $l++)
                [
                    "{{ $model->labels[$l] }}",
                    @for ($i = 0; $i < count($model->datasets); $i++)
                        {{ $model->datasets[$i]['values'][$l] }},
                    @endfor
                ],
            @endfor
        ])

        var options = {
            chart: {
              @if($model->title)
                title: "{!! $model->title !!}",
              @endif
            },
            @if($model->colors)
                colors: [
                    @foreach($model->colors as $c)
                        "{{ $c }}",
                    @endforeach
                ],
            @endif
        };

        var {{ $model->id }} = new google.charts.Bar(document.getElementById("{{ $model->id }}"))

        {{ $model->id }}.draw(data, options)
    }
</script>

@include('charts::_partials.container.div')
