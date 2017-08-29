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
                title: {
                    text: "{!! $model->x_axis_title !!}"
                },
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
                plotLines: [{
                    value: 0,
                    height: 0.5,
                    width: 1,
                    color: '#808080'
                }]
            },
            @if($model->colors)
                plotOptions: {
                    series: {
                        color: "{{ $model->colors[0] }}"
                    }
                },
            @endif
            legend: {
                @if(!$model->legend)
                    enabled: false,
                @endif
            },
            series: [{
                name: "{!! $model->element_label !!}",
                data: [
                    @foreach($model->values as $dta)
                        {{ $dta }},
                    @endforeach
                ]
            }]
        })
    });
</script>

@if(!$model->customId)
    @include('charts::_partials.container.div')
@endif
