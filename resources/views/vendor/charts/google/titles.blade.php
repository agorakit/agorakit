@if($model->title)
title: "{!! $model->title !!}",
@endif
@if($model->x_axis_title)
hAxis: {title: "{{ $model->x_axis_title }}"},
@endif
@if($model->y_axis_title)
vAxis: {title: "{{ $model->y_axis_title }}"},
@endif

