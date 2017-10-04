<div class="form-group">
    {!! Form::textarea('body', null, ['id' => 'wysiwyg', 'class' => 'form-control' , 'required']) !!}
</div>



@include('partials.mention')
@include('partials.wysiwyg')

@push ('js')
    <script>

    var at_config = {
        at: "@",
        data:[
            @foreach ($group->users as $user)
            {
                id : '{{$user->id}}',
                name : '{{$user->name}}'
            },
            @endforeach
        ],
        insertTpl: "${atwho-at}<a href=\"/users/${id}\" data-mention-user-id=\"${id}\">${name}</a>",
    }

    // Bind to every CKEditor instance that'll load in the future
    CKEDITOR.on('instanceReady', function(event) {

        var editor = event.editor;

        // Switching from and to source mode
        editor.on('mode', function(e) {
            load_atwho(this, at_config);
        });

        // First load
        load_atwho(editor, at_config);

    });

    function load_atwho(editor, at_config) {

        // WYSIWYG mode when switching from source mode
        if (editor.mode != 'source') {

            editor.document.getBody().$.contentEditable = true;

            $(editor.document.getBody().$)
            .atwho('setIframe', editor.window.getFrame().$)
            .atwho(at_config);

        }
        // Source mode when switching from WYSIWYG
        else {
            $(editor.container.$).find(".cke_source").atwho(at_config);
        }

    }



    </script>
@endpush
