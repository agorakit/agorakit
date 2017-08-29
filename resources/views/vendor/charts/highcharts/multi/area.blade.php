<script type="text/javascript">
    $(function () {
        var {{ $model->id }} = new Highcharts.Chart({
            chart: {
                type: 'area',
                renderTo:  "{{ $model->id }}",
                @include('charts::_partials.dimension.js2')
            },
            @if($model->title)
                title: {
                    text:  "{!! $model->title !!}",
                    x: -20 //center
                },
            @endif
            @if(!$model->credits)
                credits: {
                    enabled: false
                },
            @endif
            xAxis: {
                categories: [
                    @foreach($model->labels as $label)
                        "{!! $label !!}",
                    @endforeach
                ]
            },
            yAxis: {
                title: {
                    text: "{!! $model->y_axis_title === null ? $model->element_label : $model->y_axis_title !!}"
                },
            },
            legend: {
                @if(!$model->legend)
                    enabled: false,
                @endif
            },
            series: [
                @for ($i = 0; $i < count($model->datasets); $i++)
                    {
                        name:  "{!! $model->datasets[$i]['label'] !!}",
                        @if($model->colors && count($model->colors) > $i)
                            color: "{{ $model->colors[$i] }}",
                        @endif
                        data: [
                            @foreach($model->datasets[$i]['values'] as $dta)
                                {{ $dta }},
                            @endforeach
                        ]
                    },
                @endfor
            ]
        })
    });
</script>

@if(!$model->customId)
    @include('charts::_partials.container.div')
@endif
