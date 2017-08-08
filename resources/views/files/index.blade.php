@extends('app')



@section('content')

    @include('groups.tabs')


    <div class="tab_content">

        @include('partials.invite')



        <div class="toolbox">
        @can('create-file', $group)
            <a class="btn btn-primary" href="{{ action('FileController@create', $group->id ) }}">
                <i class="fa fa-file"></i>
                {{trans('messages.create_file_button')}}
            </a>
        @endcan

        @can('create-file', $group)
            <a class="btn btn-primary" href="{{ action('FileController@createLink', $group ) }}">
                <i class="fa fa-link-o"></i>
                {{trans('messages.create_link_button')}}
            </a>
        @endcan
    </div>

        <h3>
            @if (isset($file))
                <a href="{{ action('FileController@index', $group->id) }}"><i class="fa fa-home" aria-hidden="true"></i></a>
                @foreach ($file->getAncestors() as $ancestor)
                    / <a href="{{ action('FileController@show', [$group->id, $ancestor->id]) }}">{{$ancestor->name}}</a>
                @endforeach
                / {{$file->name}}
            @endif
        </h3>

        <table class="table table-hover">


            @forelse( $files as $file )
                <tr>
                    <td>
                        <a href="{{ action('FileController@show', [$group->id, $file->id]) }}"><img src="{{ action('FileController@thumbnail', [$group->id, $file->id]) }}"/></a>
                    </td>

                    <td>
                        <div class="ellipsis" style="max-width: 30em">
                            <a  href="{{ action('FileController@show', [$group->id, $file->id]) }}">{{ $file->name }}</a>
                        </div>
                    </td>



                    <td>
                        @foreach ($file->tags as $tag)
                            <span class="label label-default">{{$tag->name}}</span>
                        @endforeach
                    </td>

                    <td>
                        @unless (is_null ($file->user))
                            <a href="{{ action('UserController@show', $file->user->id) }}">{{ $file->user->name }}</a>
                        @endunless
                    </td>

                    <td>
                        {{ $file->created_at }}
                    </td>

                    <td>
                        @can('edit', $file)
                            <a class="btn btn-default btn-xs" href="{{ action('FileController@edit', [$group->id, $file->id]) }}"><i class="fa fa-edit"></i>
                                {{trans('messages.edit')}}
                            </a>
                        @endcan

                        @can('delete', $file)
                            <a class="btn btn-warning btn-xs" href="{{ action('FileController@destroyConfirm', [$group->id, $file->id]) }}"><i class="fa fa-trash"></i>
                                {{trans('messages.delete')}}
                            </a>
                        @endcan

                    </td>
                </tr>

            @empty
                {{trans('messages.nothing_yet')}}
            @endforelse

        </tbody>
    </table>


    <!--
    <a class="btn btn-default btn-xs" href="{{ action('FileController@gallery', $group->id ) }}"><i class="fa fa-camera-retro "></i>{{trans('messages.show_gallery')}}</a>
-->

</div>


@endsection
