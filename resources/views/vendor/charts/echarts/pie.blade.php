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
            name: "{!! $model->element_label !!}",
            type: 'pie',
            data: [
                @for($i = 0; count($model->values) > $i; $i++)
                    {value: {{ $model->values[$i] }}, name: "{{ $model->labels[$i] }}" },
                @endfor
            ],
            animationDelay: function (idx) {
                return idx * 100;
            }
        }],
    });
</script>
