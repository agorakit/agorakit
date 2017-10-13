@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.8.1/ui/trumbowyg.min.css" />
@endpush


@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.8.1/trumbowyg.min.js"></script>



    <script>
    $.trumbowyg.svgPath = '/svg/icons.svg';
    $('#wysiwyg').trumbowyg()


    </script>

@endpush
