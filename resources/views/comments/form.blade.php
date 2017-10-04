<div class="form-group">
    {!! Form::textarea('body', null, ['id' => 'wysiwyg', 'class' => 'form-control' , 'required']) !!}
</div>


@push ('js')
    <script>
    var tribute = new Tribute({
        values: [
            @foreach (\App\User::all() as $user)
            {key: '{{$user->name}}', value: '{{$user->name}}'},
            @endforeach
        ]
    })



    tribute.attach(document.getElementById('wysiwyg'));

    </script>
@endpush
