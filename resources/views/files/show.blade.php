@extends('app')

@section('content')

    @include('partials.grouptab')

    <div class="tab_content">

        <h2>{{trans('messages.preview_of')}} {{$file->name}}</h2>




                    <a href="{{ action('FileController@download', [$group->id, $file->id]) }}">
                        <img src="{{ action('FileController@preview', [$group->id, $file->id]) }}"/>
                    </a>


        </div>

    @endsection


    @section('head')
        {!! Html::style('https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.2.6/css/lightgallery.css') !!}
    @stop



    @section('footer')
        {!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.2.6/js/lightgallery.min.js') !!}

        <script type="text/javascript">
        $(document).ready(function() {
            $("#gallery").lightGallery({
                speed: 300
            });
        });
        </script>



    @stop
