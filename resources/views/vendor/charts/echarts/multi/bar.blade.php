@if(!$model->customId)
    @include('charts::_partials.container.div')
@endif
<script type="text/javascript">
    var {{ $model->id }} = echarts.init(document.getElementById("{{ $model->id }}"));

    {{ $model->id }}.setOption({
        title: {
            text: "{!! $model->title !!}"
        },
        tooltip: {},
        toolbox: {
            right: 30,
            feature: {
                @if ($model->export)
                    saveAsImage: {
                        title: 'Save as image',
                    }
                @endif
            }
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            top: 50,
            data: [
                @foreach ($model->datasets as $ds)
                    "{!! $ds['label'] !!}",
                @endforeach
            ]
        },
        xAxis: {
            data: [
                @foreach($model->labels as $label)
                    "{!! $label !!}",
                @endforeach
            ]
        },
        yAxis: {},
        @if (count($model->colors) > 0)
            color: [
                @foreach ($model->colors as $color)
                    "{{ $color }}",
                @endforeach
            ],
        @endif
        @if ($model->background_color)
            backgroundColor: "{{ $model->background_color }}",
        @endif
        series: [
            @foreach ($model->datasets as $ds)
                {
                    name: "{!! $ds['label'] !!}",
                    type: 'bar',
                    data: [
                        @foreach($ds['values'] as $dta)
                            {{ $dta }},
                        @endforeach
                    ],
                    animationDelay: function (idx) {
                        return idx * 100;
                    }
                },
            @endforeach
        ],
    });
</script>
