{{--
@push('js')

    <script>
    $.trumbowyg.svgPath = '/svg/icons.svg';
    $('.wysiwygxxx').trumbowyg({
        btns: [
            ['viewHTML'],
            ['undo', 'redo'], // Only supported in Blink browsers
            ['formatting'],
            ['strong', 'em'],
            ['link'],
            ['insertImage'],
            ['unorderedList', 'orderedList'],
            ['removeformat'],
            ['fullscreen']
        ],
        minimalLinks: true
    })

    </script>

@endpush

--}}
