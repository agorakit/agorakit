<div class="form-group">
    {!! Form::textarea('body', null, ['class' => 'form-control wysiwyg' , 'required']) !!}
</div>


@include('partials.wysiwyg')
@include('partials.mention')

@push ('js')
    <script>

    $('.trumbowyg-editor').atwho({
        at: "@",
        data: '{{route('groups.users.mention', $group)}}',
        insertTpl: "${atwho-at}${username}",
    });

    $('.trumbowyg-editor').atwho({
        at: "f:",
        data: '{{route('groups.files.mention', $group)}}',
        insertTpl: "<a href=\"${url}\" data-mention-file-id=\"${id}\">${name} (${atwho-at}${id})</a>",
    });

    $('.trumbowyg-editor').atwho({
        at: "d:",
        data: '{{route('groups.discussions.mention', $group)}}',
        insertTpl: "<a href=\"${url}\" data-mention-discussion-id=\"${id}\">${name} (${atwho-at}${id})</a>",
    });

    </script>
@endpush
