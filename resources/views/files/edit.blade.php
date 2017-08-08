@extends('app')

@section('content')

    @include('groups.tabs')
    <div class="tab_content">


        {!! Form::model($file, ['action' => ['FileController@update', $file->group->id, $file->id], 'files' => true]) !!}



        @include('partials.tags_form')



        <div class="form-group">
            {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
        </div>


        {!! Form::close() !!}


    </div>

@endsection
