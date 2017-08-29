@include('charts::_partials.container.svg')

<script type="text/javascript">
    $(function() {
        @include('charts::minimalist._data.one-indcolor')

        var xScale = new Plottable.Scales.Category()
        var yScale = new Plottable.Scales.Linear()

        var plot = new Plottable.Plots.Bar()
            .addDataset(new Plottable.Dataset(data))
            .x(function(d) { return d.x; }, xScale)
            .y(function(d) { return d.y; }, yScale)
            @if($model->colors)
                .attr('fill', function(d) { return d.color; })
            @endif
            .renderTo('svg#{{ $model->id }}')

        window.addEventListener('resize', function() {
            plot.redraw()
        })
    });
</script>
