<div class="form-group">
    {!! Form::textarea('body', null, ['id' => 'wysiwyg', 'class' => 'form-control' , 'required']) !!}
</div>



@include('partials.mention')

@push ('js')
    <script>

    $('#wysiwyg').atwho({
        at: "@",
        data:[
            @foreach (\App\User::all() as $user)
            {
                slug : '{{str_slug($user->name)}}',
                name : '{{$user->name}}'
            },
            @endforeach
        ],
        insertTpl: "${atwho-at}<a href=\"${slug}\">${name}</a>",
    })



    </script>
@endpush
