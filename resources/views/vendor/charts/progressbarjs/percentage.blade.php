@php($min = count($model->values) >= 2 ? $model->values[1] : 0)
@php($max = count($model->values) >= 3 ? $model->values[2] : 100)

@include('charts::_partials.container.div-titled')
@include('charts::_partials.dimension.svg')
<script type="text/javascript">
    $(function() {
        var {{ $model->id }} = new ProgressBar.Circle('#{{ $model->id }}', {
            @if($model->colors and count($model->colors) >= 2)
                color: "{{ $model->colors[1] }}",
            @else
                color: '#000',
            @endif

            // This has to be the same size as the maximum width to
            // prevent clipping
            strokeWidth: 4,
            trailWidth: 1,
            easing: 'easeInOut',
            duration: 1000,
            text: {
                autoStyleContainer: false
            },
            from: { color: '#aaa', width: 4 },
            to: { color: @if($model->colors)"{{ $model->colors[0] }}" @else '#333' @endif , width: 4 },
            // Set default step function for all animate calls
            step: function(state, circle) {
                circle.path.setAttribute('stroke', state.color)
                circle.path.setAttribute('stroke-width', state.width)
            }
        });
        {{ $model->id }}.animate({{ ($model->values[0] - $min) / ($max - $min) }});
    });
</script>
