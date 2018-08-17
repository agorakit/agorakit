@extends('app')

@section('content')

    @include('groups.tabs')
    <div class="tab_content">


        {!! Form::model($file, ['action' => ['GroupFileController@update', $file->group->id, $file->id], 'files' => true]) !!}

        <div class="form-group">
        	{!! Form::label('name', trans('messages.filename')) !!}
        	{!! Form::text('name', $file->name, ['class' => 'form-control', 'required']) !!}
        </div>

        @include('partials.tags_form')



        <div class="form-group">
            {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
        </div>


        {!! Form::close() !!}


    </div>

@endsection
