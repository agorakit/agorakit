@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        <h1>{{trans('messages.create_link')}}</h1>

        <p>{{trans('messages.create_link_help')}}</p>


        {!! Form::open(['url' => route('groups.files.createlink', $group)]) !!}


        <div class="form-group">


            <label for="link">{{trans('messages.link')}}</label>
            <input class="form-control" name="link" type="text" placeholder="http://..." value="{{ old('link') }}"/>


            <label for="title">{{trans('messages.title')}}</label>
            <input class="form-control" name="title" type="text" value="{{ old('title') }}"/>

            @include('partials.tags_form')

        </div>

        <input class="btn btn-primary" type="submit" name="{{trans('messages.create')}}"/>

        {!! Form::close() !!}


    </div>

@endsection
