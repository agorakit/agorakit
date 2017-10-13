<div class="form-group">
    {!! Form::textarea('body', null, ['id' => 'wysiwyg', 'class' => 'form-control' , 'required']) !!}
</div>



@include('partials.mention')
@include('partials.wysiwyg')

@push ('js')
    <script>

    $('.trumbowyg-editor').atwho({
        at: "@",
        data: '{{route('groups.users.mention', $group)}}',
        insertTpl: "<a href=\"${url}\" data-mention-user-id=\"${id}\">${atwho-at}${name}</a>",
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
