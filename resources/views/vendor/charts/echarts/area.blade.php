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

            data:["{!! $model->element_label !!}"]
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
            color: ["{{ $model->colors[0] }}"],
        @endif
        @if ($model->background_color)
            backgroundColor: "{{ $model->background_color }}",
        @endif
        series: [{
            name: "{!! $model->element_label !!}",
            type: 'line',
            areaStyle: {
                normal: {
                    color: "{{ count($model->colors) > 0 ? $model->colors[0] : '#c23531' }}",
                }
            },
            data: [
                @foreach($model->values as $dta)
                    {{ $dta }},
                @endforeach
            ],
            animationDelay: function (idx) {
                return idx * 100;
            }
        }],
    });
</script>
