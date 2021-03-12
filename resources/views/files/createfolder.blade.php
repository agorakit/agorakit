@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        <h1>{{trans('messages.create_folder')}}</h1>

        <p>{{trans('messages.create_folder_help')}}</p>


        {!! Form::open(['url' => route('groups.files.createfolder', ['group' => $group, 'parent' => $parent])]) !!}


        <div class="form-group">


            <label for="name">{{trans('messages.name')}}</label>
            <input class="form-control" name="name" type="text"  value="{{ old('name') }}"/>

        </div>

        <input class="btn btn-primary" type="submit" name="{{trans('messages.create')}}"/>

        {!! Form::close() !!}


    </div>

@endsection
