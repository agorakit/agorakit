@push('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Caret.js/0.3.1/jquery.caret.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/at.js/1.5.4/js/jquery.atwho.min.js"></script>
@endpush


@push('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/at.js/1.5.4/css/jquery.atwho.min.css" />
@endpush



@push ('js')
  @if (isset($group))
    <script>

    $('.trumbowyg-editor').atwho({
      at: "@",
      data: '{{route('groups.users.mention', $group)}}',
      insertTpl: "${atwho-at}${username}",
    });

    $('.trumbowyg-editor').atwho({
      at: "f:",
      data: '{{route('groups.files.mention', $group)}}',
      insertTpl: "<span>f:${id}</span>",
    });

    $('.trumbowyg-editor').atwho({
      at: "d:",
      data: '{{route('groups.discussions.mention', $group)}}',
      insertTpl: "d:${id}",
    });

    </script>
  @endif
@endpush
