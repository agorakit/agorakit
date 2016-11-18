@extends('app')



@section('content')

    @include('partials.grouptab')


    <div class="tab_content">

        @include('partials.invite')


        <div class="alert alert-info">
            Utilisez le nouveau système de gestion des fichiers <a style="color: black" href="{{ action('FileController@elfinder', [$group->id]) }}">en cliquant ici</a>
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

            @if (isset($file))
                <tr>
                    <td>
                        @if ($file->parent)
                            <a href="{{ action('FileController@show', [$group->id, $file->parent->id]) }}">
                                <i class="fa fa-level-up" aria-hidden="true"></i>
                            </a>
                        @else
                            <a href="{{ action('FileController@index', [$group->id]) }}">
                                <i class="fa fa-level-up" aria-hidden="true"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @endif

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

                    <td>
                        @unless (is_null ($file->user))
                            <a href="{{ action('UserController@show', $file->user->id) }}">{{ $file->user->name }}</a>
                        @endunless
                    </td>

                    <td>
                        {{ $file->created_at }}
                    </td>
                </tr>

            @empty
                {{trans('messages.nothing_yet')}}
            @endforelse

        </tbody>
    </table>


    <a class="btn btn-default btn-xs" href="{{ action('FileController@gallery', $group->id ) }}"><i class="fa fa-camera-retro "></i>{{trans('messages.show_gallery')}}</a>

</div>


@endsection
