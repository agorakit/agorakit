@php($min = count($model->values) >= 2 ? $model->values[1] : 0)
@php($max = count($model->values) >= 3 ? $model->values[2] : 100)

@include('charts::_partials.container.title')
<div id="{{ $model->id }}" style="position: relative;@include('charts::_partials.dimension.css')"></div>

<script type="text/javascript">
    $(function() {
        var {{ $model->id }} = new ProgressBar.Line('#{{ $model->id }}', {
            @if($model->colors and count($model->colors))
                color: "{{ $model->colors[0] }}",
            @else
                color: '#ffc107',
            @endif
            strokeWidth: 4,
            svgStyle: {width: '100%', height: '100%'},
            easing: 'easeInOut',
            duration: 1000,
            trailColor: '#eee',
            trailWidth: 4,
        })

        {{ $model->id }}.animate({{ ($model->values[0] - $min) / ($max - $min) }})
    });
</script>
