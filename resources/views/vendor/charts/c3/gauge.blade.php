@include('charts::_partials.container.div')
<script>
var {{ $model->id }} = c3.generate({
    bindto: '#{{ $model->id }}',
    data: {
      columns: [
        ["{!! $model->element_label !!}",{{ $model->values[0] }}],
    ],
    type: 'gauge',
    },
    gauge: {
        min: {{ ($model->values && count($model->values) > 1) ? $model->values[1] : '0' }},
        max: {{ ($model->values && count($model->values) > 2) ? $model->values[2] : '100' }},
    },
    axis: {
        x: {
            type: 'category',
            categories: [@foreach($model->labels as $label)"{!! $label !!}",@endforeach]
        },
        y: {
            label: {
                text: "{!! $model->element_label !!}",
                position: 'outer-middle',
            }
        },
    },
    @if($model->title)
    title: {
        text:  "{!! $model->title !!}",
        x: -20 //center
    },
    @endif
});
</script>
