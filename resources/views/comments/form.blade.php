<div class="form-group">
    {!! Form::textarea('body', null, ['class' => 'form-control wysiwyg' , 'required']) !!}
</div>

<div class="form-group">
    <label for="file">{{trans('Attach a file')}}</label>
    {!! Form::file('file', ['class' => 'form-control-file', 'id'=>'file']) !!}
</div>



@include('partials.wysiwyg')
@include('partials.mention')
