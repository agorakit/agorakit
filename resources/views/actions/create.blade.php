@extends('app')




@section('content')

    @if ($group->exists)
        @include('groups.tabs')
    @endif
    <div class="tab_content">


        <h1>{{trans('action.create_one_button')}}</h1>


        @if (!$group->exists)
            {!! Form::open(array('route' => 'actions.store')) !!}

            <div class="form-group">
                {!! Form::label('group', trans('messages.group')) !!}
                <select class="form-control" name="group" required="required">
                    <option value="" disabled selected>{{trans('messages.choose_a_group')}}</option>
                    @foreach (Auth::user()->groups as $group)
                        <option value="{{$group->id}}">{{$group->name}}</option>
                    @endforeach
                </select>
            </div>

            @include('actions.form')

            <div class="form-group">
                {!! Form::submit(trans('messages.create'), ['class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}

        @else
            {!! Form::model($action, array('action' => ['GroupActionController@store', $group])) !!}

            @include('actions.form')

            <div class="form-group">
                {!! Form::submit(trans('messages.create'), ['class' => 'btn btn-primary']) !!}
            </div>



            {!! Form::close() !!}

        @endif










    </div>


@endsection
