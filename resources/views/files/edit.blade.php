@extends('app')

@section('content')

    @include('groups.tabs')
    <div class="tab_content">


        {!! Form::model($file, ['action' => ['FileController@update', $file->group->id, $file->id], 'files' => true]) !!}



        <div class="form-group">
            <label for="tags">{{trans('messages.tags')}}</label>
            <input class="form-control" name="tags" type="text" value="{{$tags}}"/>
        </div>


        <div class="form-group">
            {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
        </div>


        {!! Form::close() !!}


    </div>

@endsection
