<script type="text/javascript">
    FusionCharts.ready(function () {
        var {{ $model->id }} = new FusionCharts({
            type: 'msarea',
            renderAt: "{{ $model->id }}",
            @include('charts::_partials.dimension.js')
            dataFormat: 'json',
            dataSource: {
                'chart': {
                    "exportenabled": "1",
                    "exportatclient": "1",
                    @if($model->title)
                    'caption': "{!! $model->title !!}",
                    @endif
                    'yAxisName': "{!! $model->element_label !!}",
                    'bgColor': '#ffffff',
                    'borderAlpha': '20',
                    'canvasBorderAlpha': '0',
                    'usePlotGradientColor': '0',
                    'plotBorderAlpha': '10',
                    'showValues': '0',
                    'showValues': '0',
                    'valueFontColor': '#ffffff',
                    'showXAxisLine': '1',
                    'xAxisLineColor': '#999999',
                    'divlineColor': '#999999',
                    'divLineIsDashed': '1',
                    'showAlternateHGridColor': '0',
                    'subcaptionFontBold': '0',
                    'subcaptionFontSize': '14'
                },
                'categories': [{
                    'category': [
                        @foreach($model->labels as $l)
                            {
                                'label': "{{ $l }}",
                            },
                        @endforeach
                    ]
                }],
                'dataset': [
                    @for ($i = 0; $i < count($model->datasets); $i++)
                        {
                            'seriesname': "{{ $model->datasets[$i]['label'] }}",
                            @if($model->colors and count($model->colors) > $i)
                                'color': "{{ $model->colors[$i] }}",
                            @endif
                            'data': [
                                @foreach($model->datasets[$i]['values'] as $v)
                                    {
                                        'value': {{ $v }}
                                    },
                                @endforeach
                            ]
                        },
                    @endfor
                ]
            }
        }).render()
    });
</script>

@include('charts::_partials.container.div')
