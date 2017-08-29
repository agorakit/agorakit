@include('charts::_partials.container.svg')

<script type="text/javascript">
    $(function() {
        @include('charts::plottablejs._data.one')

        var xScale = new Plottable.Scales.Category()
        var yScale = new Plottable.Scales.Linear()

        var xAxis = new Plottable.Axes.Category(xScale, 'bottom')
        var yAxis = new Plottable.Axes.Numeric(yScale, 'left')

        var reverseMap = {};
        data.forEach(function(d) { reverseMap[d.y] = d.x;});

        var plot = new Plottable.Plots.Pie()
            .addDataset(new Plottable.Dataset(data))
            .sectorValue(function(d) { return d.y; }, yScale)
            @if($model->colors)
                .attr('fill', function(d) { return d.color; })
            @endif
            .innerRadius(250, yScale)
            .outerRadius(500, yScale)
            .labelsEnabled(true)
            .labelFormatter(function(n){ return reverseMap[n] ;})
            .animated(true);

        var title;
        @if($model->title)
            title = new Plottable.Components.TitleLabel("{!! $model->title !!}").yAlignment('center');
        @endif

        var table = new Plottable.Components.Table([[title],[plot]]);
        table.renderTo('svg#{{ $model->id }}');

        window.addEventListener('resize', function() {
            table.redraw()
        })
    });
</script>

