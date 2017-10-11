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
            data:[
                @foreach ($group->users as $user)
                {
                    id : '{{$user->id}}',
                    name : '{{$user->name}}',
                    url: '{{route('users.show', [$user])}}'
                },
                @endforeach
            ],
            insertTpl: "<a href=\"${url}\" data-mention-user-id=\"${id}\">${atwho-at}${name}</a>",
        },
        {

            at: "f:",
            data:[
                @foreach ($group->files()->orderBy('created_at', 'desc')->get() as $file)
                {
                    id : '{{$file->id}}',
                    name : '{{$file->name}}',
                    url: '{{route('groups.files.show', [$group, $file])}}'
                },
                @endforeach
            ],
            insertTpl: "<a href=\"${url}\" data-mention-file-id=\"${id}\">${name} (${atwho-at}${id})</a>",
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
