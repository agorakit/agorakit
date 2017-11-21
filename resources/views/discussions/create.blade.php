@extends('app')

@section('content')

    @if ($group->exists)
        @include('groups.tabs')
    @endif


    <div class="tab_content">

        <h1>{{trans('messages.create')}}</h1>



        @if (!$group->exists)
            {!! Form::open(array('route' => 'discussions.store')) !!}

            <div class="form-group">
                {!! Form::label('group', trans('messages.group')) !!}
                <select class="form-control" name="group">
                    @foreach (Auth::user()->groups as $group)
                        <option value="{{$group->id}}">{{$group->name}}</option>
                    @endforeach
                </select>
            </div>

            @include('discussions.form')

            <div class="form-group">
                {!! Form::submit(trans('messages.create'), ['class' => 'btn btn-primary form-control']) !!}
            </div>

            {!! Form::close() !!}

        @else
            {!! Form::open(array('action' => ['DiscussionController@store', $group->id])) !!}

            @include('discussions.form')

            <div class="form-group">
                {!! Form::submit(trans('messages.create'), ['class' => 'btn btn-primary form-control']) !!}
            </div>

            {!! Form::close() !!}

        @endif




    </div>

@endsection
