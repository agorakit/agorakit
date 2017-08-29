@include('charts::_partials.container.div-titled')

<script type="text/javascript">
    $(function (){
        Morris.Bar({
            element: "{{ $model->id }}",
            resize: true,
            data: [
                @for($k = 0; $k < count($model->labels); $k++)
                    {
                        x: "{{ $model->labels[$k] }}",
                        @for ($i = 0; $i < count($model->datasets); $i++)
                            s{{ $i }}: "{{ $model->datasets[$i]['values'][$k] }}",
                        @endfor
                    },
                @endfor
            ],
            xkey: 'x',
            labels: [
                @for ($i = 0; $i < count($model->datasets); $i++)
                    "{{ $model->datasets[$i]['label'] }}",
                @endfor
            ],
            ykeys: [
                @for($i = 0; $i < count($model->datasets); $i++)
                    "s{{ $i }}",
                @endfor
            ],
            hideHover: 'auto',
            parseTime: false,
            @if($model->colors)
                barColors: [
                    @foreach($model->colors as $c)
                        "{{ $c }}",
                    @endforeach
                ],
            @endif
        })
    });
</script>
