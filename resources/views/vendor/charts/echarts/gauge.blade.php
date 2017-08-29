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

            top: 50,
            data: [
                @foreach($model->labels as $label)
                    "{!! $label !!}",
                @endforeach
            ]
        },
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
        series: [{
            name: "{!! $model->title !!}",
            type: 'gauge',
            
            min: {{ ($model->values && count($model->values) > 1) ? $model->values[1] : '0' }},
            max: {{ ($model->values && count($model->values) > 2) ? $model->values[2] : '100' }},
            data: [
                {
                    value: {{ $model->values[0] }},
                    name: "{!! $model->element_label !!}",
                }
            ],
            animationDelay: function (idx) {
                return idx * 100;
            }
        }],
    });
</script>
