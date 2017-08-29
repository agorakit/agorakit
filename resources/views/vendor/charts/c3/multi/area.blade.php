@include('charts::_partials.container.div')
<script>
var {{ $model->id }} = c3.generate({
    bindto: '#{{ $model->id }}',
    data: {
        columns: [
            @foreach($model->datasets as $ds)
                ["{{ $ds['label'] }}",@foreach($ds['values'] as $value){{ $value }},@endforeach],
            @endforeach
        ],
        type: 'area',
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
