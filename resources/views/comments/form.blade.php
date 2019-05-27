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
        insertTpl: "<span>f:${id}</span>",
    });

    $('.trumbowyg-editor').atwho({
        at: "d:",
        data: '{{route('groups.discussions.mention', $group)}}',
        insertTpl: "d:${id}",
    });

    </script>
@endpush
