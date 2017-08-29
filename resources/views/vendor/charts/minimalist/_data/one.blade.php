var data = [
    @for($i = 0; $i < count($model->values); $i++)
        {
            x: "{!! $model->labels[$i] !!}",
            y: {{ $model->values[$i] }},
        },
    @endfor
];
