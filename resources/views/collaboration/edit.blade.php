<!DOCTYPE html>
<html style="height: 100%; margin: 0;">

<head>

</head>

<body style="height: 100%; margin: 0;">


    <script type="text/javascript" src="{{config('agorakit.onlyoffice_url')}}/web-apps/apps/api/documents/api.js">
    </script>


    <div id="placeholder" style="height:100%">The editor will load here</div>


    <script>
        new DocsAPI.DocEditor("placeholder", {
        "document": {
            "fileType": "docx",
            "key": "{{$file->id}}",
            "title": "{{$file->name}}",
            "url": "{!!URL::temporarySignedRoute('groups.files.collaboration.download', now()->addMinutes(30), [$group, $file]) !!}"            
        },
        "documentType": "word"
    });
    
    </script>

</body>

</html>