<script type="text/javascript">
    FusionCharts.ready(function () {
        var {{ $model->id }} = new FusionCharts({
            type: 'doughnut2d',
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
                    'paletteColors': '#0075c2',
                    'bgColor': '#ffffff',
                    'showBorder': '0',
                    'use3DLighting': '0',
                    'showShadow': '0',
                    'enableSmartLabels': '1',
                    'startingAngle': '0',
                    'showPercentValues': '1',
                    'showPercentInTooltip': '0',
                    'decimals': '1',
                    'captionFontSize': '14',
                    'subcaptionFontSize': '14',
                    'subcaptionFontBold': '0',
                    'toolTipColor': '#ffffff',
                    'toolTipBorderThickness': '0',
                    'toolTipBgColor': '#000000',
                    'toolTipBgAlpha': '80',
                    'toolTipBorderRadius': '2',
                    'toolTipPadding': '5',
                    'showHoverEffect':'1',
                    'showLegend': '1',
                    'legendBgColor': '#ffffff',
                    'legendBorderAlpha': '0',
                    'legendShadow': '0',
                    'legendItemFontSize': '10',
                    'legendItemFontColor': '#666666'
                },
                'data': [
                    @for ($i = 0; $i < count($model->values); $i++)
                        {
                            'label': "{!! $model->labels[$i] !!}",
                            'value': {{ $model->values[$i] }},
                            @if($model->colors)
                                'color': "{{ $model->colors[$i] }}",
                            @endif
                        },
                    @endfor
                ],
            }
        }).render()
    });
</script>

@include('charts::_partials.container.div')
