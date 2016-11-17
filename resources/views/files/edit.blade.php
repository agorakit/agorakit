@extends('app')

@section('content')

    @include('partials.grouptab')
    <div class="tab_content">


        {!! Form::model($file, ['action' => ['FileController@update', $file->group->id, $file->id], 'files' => true]) !!}



        <div class="form-group">
            Déplacer ce dossier / document dans le dossier suivant :

            <select name="parent_id">
                @foreach ($folders as $folder)
                    <option value="{{$folder->id}}">{{$folder->name}}</option>
                @endforeach
            </select>


        </div>


        <div class="form-group">
            {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
        </div>


        {!! Form::close() !!}


    </div>

@endsection
