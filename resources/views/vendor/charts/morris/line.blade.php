@include('charts::_partials/container.div-titled')

<script type="text/javascript">
    $(function (){
        Morris.Line({
            element: "{{ $model->id }}",
            resize: true,
            data: [
                @for($l = 0; $l < count($model->values); $l++)
                    {
                        x: "{{ $model->labels[$l] }}",
                        y: "{{ $model->values[$l] }}"
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
