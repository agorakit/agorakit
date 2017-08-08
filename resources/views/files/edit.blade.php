@extends('app')

@section('content')

    @include('groups.tabs')
    <div class="tab_content">


        {!! Form::model($file, ['action' => ['FileController@update', $file->group->id, $file->id], 'files' => true]) !!}





        <div class="form-group">
            <label for="tags">{{trans('messages.tags')}}</label>
            <select class="form-control tags" name="tags[]" multiple="multiple">
                @foreach ($file->tags as $tag)
                    <option selected="selected" value="{{$tag->name}}">{{$tag->name}}</option>
                @endforeach

                @foreach ($all_tags as $tag)
                    <option value="{{$tag}}">{{$tag}}</option>
                @endforeach

            </select>
        </div>

        @section('css')
            <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
        @endsection

        @section('js')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


            <script>
            $(".tags").select2({
                tags: true,
                tokenSeparators: [',']
            })
            </script>

        @endsection


        <div class="form-group">
            {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
        </div>


        {!! Form::close() !!}


    </div>

@endsection
