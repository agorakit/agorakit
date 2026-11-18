@extends('dialog')

@section('content')
    <h1>{{ trans('Are you sure you want to delete?') }} : <strong>{{ $file->name }}</strong>



    </h1>

    {{-- @if ($file->hasChildren())

<div class=" alert alert-info primary">This file contains the following items :
@foreach ($file->children as $file)
<div>@include('files.button')</div>
@endforeach

<div><input type="radio"/> I want to delete all children</div>
<div><input type="radio"/> Move all children to root</div>

</div>



@endif --}}

    {!! Form::model($file, ['method' => 'DELETE', 'action' => ['GroupFileController@destroy', $group, $file]]) !!}


    <div class="flex justify-content-between mt-5">
        <div class="form-group">
            {!! Form::submit(trans('Delete'), ['class' => 'btn btn-danger']) !!}
        </div>
        <div>
            <a class="btn btn-link js-back" up-dismiss up-dismiss>{{ trans('Cancel') }}</a>
        </div>
    </div>


    {!! Form::close() !!}
@endsection
