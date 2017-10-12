<div class="form-group">
    {!! Form::textarea('body', null, ['id' => 'wysiwyg', 'class' => 'form-control' , 'required']) !!}
</div>



@include('partials.mention')
@include('partials.wysiwyg')

@push ('js')
    <script>
    var all_config = [
        {
            at: "@",
            data: '{{route('groups.users.mention', $group)}}',
            insertTpl: "<a href=\"${url}\" data-mention-user-id=\"${id}\">${atwho-at}${name}</a>",
        },
        {
            at: "f:",
            data: '{{route('groups.files.mention', $group)}}',
            insertTpl: "<a href=\"${url}\" data-mention-file-id=\"${id}\">${name} (${atwho-at}${id})</a>",
        },
        {
            at: "d:",
            data: '{{route('groups.discussions.mention', $group)}}',
            insertTpl: "<a href=\"${url}\" data-mention-discussion-id=\"${id}\">${name} (${atwho-at}${id})</a>",
        }
    ];

    // Bind to every CKEditor instance that'll load in the future
    CKEDITOR.on('instanceReady', function(event) {

        var editor = event.editor;

        // Switching from and to source mode
        editor.on('mode', function(e) {
            load_atwho(this, all_config);

        });

        // First load
        load_atwho(editor, all_config);

    });

    function load_atwho(editor, at_config) {

        // WYSIWYG mode when switching from source mode
        if (editor.mode != 'source') {

            editor.document.getBody().$.contentEditable = true;

            var my_element = $(editor.document.getBody().$)
            .atwho('setIframe', editor.window.getFrame().$);


            $.each(at_config, function(key, value) {
                my_element.atwho(value);
            });
        }
        // Source mode when switching from WYSIWYG
        else {
            var my_element = $(editor.container.$).find(".cke_source");
            $.each(at_config, function(key, value) {
                my_element.atwho(value);
            });
        }
    }

    </script>
@endpush
