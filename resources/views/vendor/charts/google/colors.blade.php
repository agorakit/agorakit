@if($model->colors)
colors:[
    @foreach($model->colors as $color)
    "{{ $color }}",
    @endforeach
],
@endif
@if($model->background_color)
backgroundColor: "{{ $model->background_color }}",
@endif
