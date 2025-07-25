@extends('app')

@section('content')
    <h2>{{ trans('messages.files_in_this_group') }}

        @can('create-file', $group)
            <a class="btn btn-primary btn-xs" href="{{ route('groups.files.create', $group) }}">
                <i class="fa fa-plus"></i>
                {{ trans('messages.create_file_button') }}
            </a>
        @endcan

    </h2>

    <p>
        <a class="btn btn-primary btn-xs" href="{{ route('groups.files.index', $group) }}">
            <i class="fa fa-list "></i>
            {{ trans('messages.show_list') }}</a>
    </p>

    <div id="gallery">
        @forelse($files as $file)
            <a href="{{ route('groups.files.download', [$group, $file]) }}">
                <img alt="{{ $file->name }}" src="{{ route('groups.files.preview', [$group, $file]) }}" />
            </a>
        @empty
            {{ trans('messages.nothing_yet') }}
        @endforelse
    </div>

    {!! $files->render() !!}

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
