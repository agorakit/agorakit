@include('charts::_partials.container.svg')

<script type="text/javascript">
    $(function() {
        @include('charts::plottablejs._data.one')

        var xScale = new Plottable.Scales.Category();
        var yScale = new Plottable.Scales.Linear();

        var xAxis = new Plottable.Axes.Category(xScale, 'bottom');
        var yAxis = new Plottable.Axes.Numeric(yScale, 'left');

        var plot = new Plottable.Plots.Bar()
            .addDataset(new Plottable.Dataset(data))
            .x(function(d) { return d.x; }, xScale)
            .y(function(d) { return d.y; }, yScale)
            @if($model->colors)
                .attr('fill', function(d) { return d.color; })
            @endif
            .animated(true);

        var title;
        @if($model->title)
            title = new Plottable.Components.TitleLabel("{!! $model->title !!}").yAlignment('center');
        @endif
        var label = new Plottable.Components.AxisLabel("{!! $model->element_label !!}").yAlignment('center').angle(270);

        var table = new Plottable.Components.Table([[null,null, title],[label, yAxis, plot],[null, null, xAxis]]);

        table.renderTo('svg#{{ $model->id }}');

        window.addEventListener('resize', function() {
            table.redraw()
        })
    });
</script>
