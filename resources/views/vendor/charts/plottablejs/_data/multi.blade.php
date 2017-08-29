@for($i = 0; $i < count($model->datasets); $i++)
    var s{{ $i }} = [
        @for($k = 0; $k < count($model->datasets[$i]['values']); $k++)
            {
                x: "{{ $model->labels[$k] }}",
                y: {{ $model->datasets[$i]['values'][$k] }},
            },
        @endfor
    ];
@endfor
