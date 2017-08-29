@if($model->responsive)
    height="100%" width="100%"
@else
    @if($model->height)
        height="{{ $model->height }}"
    @endif

    @if($model->width)
        width="{{ $model->width }}"
    @endif
@endif
