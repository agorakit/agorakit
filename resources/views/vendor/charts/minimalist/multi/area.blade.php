@include('charts::_partials.container.svg')

<script type="text/javascript">
    $(function() {
        @include('charts::minimalist._data.multi')

        var xScale = new Plottable.Scales.Category()
        var yScale = new Plottable.Scales.Linear()

        var plot = new Plottable.Plots.Area()
            @for($i = 0; $i < count($model->datasets); $i++)
                .addDataset(new Plottable.Dataset(s{{ $i }}))
            @endfor
            .x(function(d) { return d.x; }, xScale)
            .y(function(d) { return d.y; }, yScale)
            @if($model->colors)
                .attr('stroke', "{{ $model->colors[0] }}")
                .attr('fill', "{{ $model->colors[0] }}")
            @endif
            .renderTo('svg#{{ $model->id }}')

        window.addEventListener('resize', function() {
            plot.redraw()
        })
    });
</script>

