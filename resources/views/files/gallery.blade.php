@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        <h2>{{trans('messages.files_in_this_group')}}

            @can('create-file', $group)
                <a class="btn btn-primary btn-xs" href="{{ action('FileController@create', $group->id ) }}">
                    <i class="fa fa-plus"></i>
                    {{trans('messages.create_file_button')}}
                </a>
            @endcan

        </h2>

        <p>
            <a class="btn btn-default btn-xs" href="{{ action('FileController@index', $group->id ) }}">
                <i class="fa fa-list "></i>
                {{trans('messages.show_list')}}</a>
            </p>


            <div id="gallery">
                @forelse( $files as $file )
                    <a href="{{ action('FileController@download', [$group->id, $file->id]) }}">
                        <img src="{{ action('FileController@preview', [$group->id, $file->id]) }}"/>
                    </a>
                @empty
                    {{trans('messages.nothing_yet')}}
                @endforelse
            </div>

            {!! $files->render() !!}

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
