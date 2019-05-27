@push('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tributejs/3.7.1/tribute.min.js"></script>
@endpush


@push('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tributejs/3.7.1/tribute.min.css" />
@endpush



@push ('js')
  <script>

  var tribute = new Tribute({
    requireLeadingSpace: false,
    trigger: '#',
    values: [
      {key: 'Fichier : Phil Heartman', value: 'f:23'},
      {key: 'Gordon Ramsey', value: 'gramsey'}
    ],
    searchOpts: {
      pre: '',
      post: ''
    }
  })

  tribute.attach(document.querySelectorAll('.trumbowyg-editor'));


</script>
@endpush
