<script type="text/javascript">
    google.charts.setOnLoadCallback(draw{{ $model->id }})

    function draw{{ $model->id }}() {
        var data = google.visualization.arrayToDataTable([
            ['', "{!! $model->element_label !!}",
                @if($model->colors)
                    { role: 'style' }
                @endif
            ],
            @for ($i = 0; $i < count($model->values); $i++)
                [
                    "{!! $model->labels[$i] !!}", {{ $model->values[$i] }}
                    @if($model->colors)
                        ,"{{ $model->colors[$i] }}"
                    @endif
                ],
            @endfor
        ])

        var options = {
            @include('charts::_partials.dimension.js')
            legend: { position: 'top', alignment: 'end' },
            fontSize: 12,
            @include('charts::google.titles')
            @if($model->colors)
                colors:[
                    @foreach($model->colors as $color)
                        "{{ $color}}",
                    @endforeach
                ],
            @endif
        };

        var {{ $model->id }} = new google.visualization.ColumnChart(document.getElementById("{{ $model->id }}"))

        {{ $model->id }}.draw(data, options)
    }
</script>

@if(!$model->customId)
    @include('charts::_partials.container.div')
@endif
