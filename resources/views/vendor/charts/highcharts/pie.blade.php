<script type="text/javascript">
    $(function () {
        var {{ $model->id }} = new Highcharts.Chart({
            colors: [
                @foreach($model->colors as $c)
                    "{{ $c }}",
                @endforeach
            ],
            chart: {
                renderTo: "{{ $model->id }}",
                @include('charts::_partials.dimension.js2')
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            @if($model->title)
                title: {
                    text:  "{!! $model->title !!}",
                },
            @endif
            @if(!$model->credits)
                credits: {
                    enabled: false
                },
            @endif
            tooltip: {
                pointFormat: '{point.y} <b>({point.percentage:.1f}%)</strong>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</strong>: {point.y} ({point.percentage:.1f}%)',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            legend: {
                @if(!$model->legend)
                    enabled: false
                @endif
            },
            series: [{
                colorByPoint: true,
                data: [
                    @for($i = 0; $i < count($model->values); $i++)
                        {
                            name: "{!! $model->labels[$i] !!}",
                            y: {{ $model->values[$i] }}
                        },
                    @endfor
                ]
            }]
        })
    });
</script>

@if(!$model->customId)
    @include('charts::_partials.container.div')
@endif
