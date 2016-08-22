@extends('app')

@section('content')

    @include('partials.grouptab')
    <div class="tab_content">
        <h1>Tag</h1>


        {!! Form::model($file, ['action' => ['FileController@update', $file->group->id, $file->id], 'files' => true]) !!}



        <div class="form-group">

            <select id="tags" name="tags[]" multiple="multiple" class="form-control input-lg">
                @foreach ($file->tags as $tag)
                    <option value="{{$tag->name}}" selected="selected">{{$tag->name}}</option>
                @endforeach

            </select>




        </div>


        <div class="form-group">
            {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
        </div>


        {!! Form::close() !!}


    </div>

@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script type="text/javascript">

    $('#tags').select2(
        {
            tags: true
        }
    );

    </script>
@endsection

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection
