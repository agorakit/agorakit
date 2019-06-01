@extends('app')

@section('content')

    @include('groups.tabs')


    <div class="toolbox d-md-flex">

        <div>
            @lang('Add or remove tags to be used to classify content in this group.')
            
            @lang('Colors are shared between all groups')
        </div>

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



    <div class="tags items">
        <table class="table">
            @forelse( $tags as $tag )
                <tr>
                    <td>
                        <h2>@include('tags.tag')</h2>
                    </td>
                    <td>
                        <a class="btn btn-primary" href="{{ route('groups.tags.edit', [$group, $tag] ) }}">
                            <i class="fas fa-edit"></i>
                            @lang('Edit')
                        </a>
                        <a class="btn btn-warning" href="{{ route('groups.tags.deleteconfirm', [$group, $tag] ) }}">
                            <i class="fas fa-trash"></i>
                            @lang('Remove')
                        </a>
                    </td>

                </tr>
            @empty
                {{trans('messages.nothing_yet')}}
            @endforelse
        </table>


    </div>



@endsection
