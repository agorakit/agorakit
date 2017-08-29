@if(!$model->customId)
    @include('charts::_partials.container.div')
@endif

<script>
var {{ $model->id }} = AmCharts.makeChart( "{{ $model->id }}", {
    "type": "serial",
    "theme": "light",
    "dataProvider": [
        @foreach($model->values as $v)
        {
            "country": "{{ $model->labels[$loop->index] }}",
            "visits": {{ $v }}
        },
        @endforeach
    ],
    "valueAxes": [
        {
            "minimum": 0,
            "title": "{!! $model->element_label !!}",
        }
    ],
  "gridAboveGraphs": true,
  "startDuration": 1,
  "graphs": [ {
    "balloonText": "[[category]]: <b>[[value]]</b>",
    "fillAlphas": 0.8,
    "lineAlpha": 0.2,
    "type": "column",
    "valueField": "{!! $model->element_label !!}"
  } ],
  "chartCursor": {
    "categoryBalloonEnabled": false,
    "cursorAlpha": 0,
    "zoomable": false
  },
  "categoryField": "country",
  "categoryAxis": {
    "gridPosition": "start",
    "gridAlpha": 0,
    "tickPosition": "start",
    "tickLength": 20
  },
  "export": {
    "enabled": true
  }

} );
</script>
