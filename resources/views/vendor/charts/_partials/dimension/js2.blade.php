@if(!$model->responsive)
    @if($model->height)
        height: {{ $model->height }},
    @endif

    @if($model->width)
        width: {{ $model->width }},
    @endif
@endif
