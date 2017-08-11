@extends('app')




@section('content')

    @include('groups.tabs')
    <div class="tab_content">



        <h1>{{trans('action.create_one_button')}}</h1>


        {!! Form::model($action, array('action' => ['ActionController@store', $group->id])) !!}

        @include('actions.form')

        <div class="form-group">
            {!! Form::submit(trans('messages.create'), ['class' => 'btn btn-primary form-control']) !!}
        </div>



        {!! Form::close() !!}



    </div>


@endsection
