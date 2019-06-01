@extends('app')

@section('content')

    @include('groups.tabs')

    @auth
        <div class="toolbox d-md-flex">

            <div class="ml-auto">
                @can('manage-tags', $group)
                    <div class="toolbox">
                        <a class="btn btn-primary" href="{{ route('groups.tags.create', $group ) }}">
                            <i class="fa fa-plus"></i> {{trans('Add a tag')}}
                        </a>
                    </div>
                @endcan
            </div>
        </div>
    @endauth


    <div class="tags items">
        <table class="table">
            @forelse( $tags as $tag )
                <tr>
                    <td>
                        <a class="btn btn-primary" href="{{ route('groups.tags.show', [$group, $tag] ) }}">{{$tag->name}}</a>
                    </td>
                    <td>
                        <a class="btn btn-secondary" href="{{ route('groups.tags.edit', [$group, $tag] ) }}">
                            <i class="fas fa-edit"></i>
                            @lang('Edit')
                        </a>
                        <a class="btn btn-warning" href="{{ route('groups.tags.deleteconfirm', [$group, $tag] ) }}">
                            <i class="fas fa-trash"></i>
                            @lang('Delete')
                        </a>
                    </td>

                </tr>
            @empty
                {{trans('messages.nothing_yet')}}
            @endforelse
        </table>


    </div>



@endsection
