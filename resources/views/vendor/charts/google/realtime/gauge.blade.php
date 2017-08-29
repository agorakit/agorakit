<script type="text/javascript">
    google.charts.setOnLoadCallback(draw{{ $model->id }})

    function draw{{ $model->id }}() {
        var data = google.visualization.arrayToDataTable([
            ['Element', 'Value'],
            @if($model->values)
                ["{!! $model->element_label !!}", {{ $model->values[0] }}],
            @else
                ["{!! $model->element_label !!}", 0],
            @endif
        ])
        var options = {
            @include('charts::_partials.dimension.js')

            @if(count($model->values) >= 2 and $model->values[1] <= $model->values[0])
                @php($min = $model->values[1])
                min: {{ $min }},
            @else
                @php($min = 0)
            @endif

            @if(count($model->values) >= 3 and $model->values[2] >= $model->values[0])
                @php($max = $model->values[2])
                max: {{ $max }},
            @else
                @php($max = 100)
            @endif

            @if($model->gauge_style == 'right')
                // Calculate warning area
                @php
                    $low_warning = round(0.40 * $max, 2);
                    $warning = round(0.25 * $max, 2);
                    $max_warning = round(0.10 * $max, 2);
                @endphp

                greenColor: '#c8e6c9', yellowColor: '#ffd54f', redColor: '#e57373',
                greenFrom: {{ $low_warning }}, greenTo: {{ $max }},
                yellowFrom: {{ $max_warning }}, yellowTo: {{ $low_warning }},
                redFrom: {{ $min }}, redTo: {{ $max_warning }},
            @elseif($model->gauge_style == 'center') {
                // Calculate warning area
                @php
                    $warning = round(0.25 * $max, 2);
                    $warning2 = round(0.75 * $max, 2);
                @endphp

                greenColor: '#c8e6c9', yellowColor: '#ffd54f', redColor: '#ffd54f',
                greenFrom: {{ $warning }}, greenTo: {{ $warning2 }},
                yellowFrom: {{ $min }}, yellowTo: {{ $warning }},
                redFrom: {{ $warning2 }}, redTo: {{ $max }},
            @else
                // Calculate warning area
                @php
                    $low_warning = round(0.60 * $max, 2);
                    $warning = round(0.75 * $max, 2);
                    $max_warning = round(0.90 * $max, 2);
                @endphp

                greenColor: '#c8e6c9', yellowColor: '#ffd54f', redColor: '#e57373',
                greenFrom: {{ $min }}, greenTo: {{ $low_warning }},
                yellowFrom: {{ $low_warning }}, yellowTo: {{ $max_warning }},
                redFrom: {{ $max_warning }}, redTo: {{ $max }},
            @endif

            minorTicks: 10,
        };

        var {{ $model->id }} = new google.visualization.Gauge(document.getElementById("{{ $model->id }}"))
        {{ $model->id }}.draw(data, options)

        setInterval(function() {
            $.getJSON("{{ $model->url }}", function( jdata ) {
                data.setValue(0, 1, jdata["{{ $model->value_name }}"])
                {{ $model->id }}.draw(data, options)
            })
        }, {{ $model->interval }})
    }
</script>

@if(!$model->customId)
    @include('charts::_partials/container.div-titled')
@endif
