@include('charts::_partials/container.div-titled')

<script type="text/javascript">
    $(function () {
        Morris.Bar({
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
            @if($model->colors)
                barColors: function (row, series, type) {
                    @for ($i = 0; $i < count($model->colors); $i++)
                        @if($i == 0)
                            if(row.label == "{{ $model->labels[$i] }}") return "{{ $model->colors[$i] }}"
                        @else
                            else if(row.label == "{{ $model->labels[$i] }}") return "{{ $model->colors[$i] }}"
                        @endif
                    @endfor
                }
            @endif
        })
    });
</script>
