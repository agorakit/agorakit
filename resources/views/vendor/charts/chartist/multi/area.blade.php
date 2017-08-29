@include('charts::_partials.container.chartist')

<script type="text/javascript">
    var data = {
        labels: [
            @foreach($model->labels as $label)
                "{!!  $label !!}",
            @endforeach
        ],
        series: [
            @foreach($model->datasets as $ds)
                [
                    @foreach($ds['values'] as $value)
                        "{{ $value }}",
                    @endforeach
                ],
            @endforeach
        ]
    };

    var options = {
        showArea: true,
        @include('charts::_partials.dimension.js')
    }
    new Chartist.Line('#{{ $model->id }}', data, options);
</script>
