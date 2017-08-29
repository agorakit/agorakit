@include('charts::_partials/container.div-titled')

<script type="text/javascript">
    $(function() {
        Morris.Area({
            element: "{{ $model->id }}",
            resize: true,
            data: [
                @for ($i = 0; $i < count($model->values); $i++)
                    {
                        x: "{!! $model->labels[$i] !!}",
                        y: {{ $model->values[$i] }}
                    },
                @endfor
            ],
            xkey: 'x',
            ykeys: ['y'],
            labels: ["{!! $model->element_label !!}"],
            hideHover: 'auto',
            parseTime: false,
            @if($model->colors)
                lineColors: ["{{ $model->colors[0] }}"],
            @endif
        })
    });
</script>
