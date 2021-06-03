@extends('app')

@section('content')

    @include('groups.tabs')
    <div class="tab_content">


        {!! Form::model($file, ['action' => ['GroupFileController@update', $file->group, $file], 'files' => true]) !!}

        <div class="form-group">
        	{!! Form::label('name', trans('messages.filename')) !!}
        	{!! Form::text('name', $file->name, ['class' => 'form-control', 'required']) !!}
        </div>


        @if ($file->isFile())
        <div class="form-group mt-4 mb-4">
            {!! Form::label('file', trans('messages.upload_new_file_version')) !!}
            <input name="file" id="file" type="file" class="form-control"
                title="{{ trans('messages.upload_new_file_version') }}">
        </div>
        @endif


        @if ($folders->count() > 0) 
            <div class="form-group">
            	{!! Form::label('parent', trans('messages.folder')) !!}
                <select  name="parent" class="form-control">
                <option value="root" @if ($file->parent_id == null) selected="selected"@endif>{{trans('messages.root')}}</option>
                @foreach ($folders as $folder)
                    <option value="{{$folder->id}}" @if ($file->parent_id == $folder->id) selected="selected"@endif>{{$folder->name}}</option>
                @endforeach
                 </select>
            </div>
        @endif

        @include('partials.tags_input')



        <div class="form-group">
            {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary btn-lg']) !!}
        </div>


        {!! Form::close() !!}


    </div>

@endsection
