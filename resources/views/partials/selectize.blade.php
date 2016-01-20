{!! Html::style('/packages/selectize/css/selectize.bootstrap3.css') !!}
{!! Html::script('/packages/selectize/js/selectize.min.js') !!}

@section('footer')
<script>
$( document ).ready(function() {
  $('#tags').selectize({
    delimiter: ',',
    persist: false,
    create: function(input) {
      return {
        value: input,
        text: input
      }
    }
  });
});
</script>
@stop
