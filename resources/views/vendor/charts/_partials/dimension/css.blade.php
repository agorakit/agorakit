@if($model->responsive)
    height: 100%; width: 100%;
@else
    @if($model->height)
        height: {{ $model->height }}px;
    @endif

    @if($model->width)
        width: {{ $model->width }}px;
    @endif
@endif
