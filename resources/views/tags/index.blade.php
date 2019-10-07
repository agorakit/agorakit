@extends('app')



@section('content')

    @include('groups.tabs')



    @if ($group->tagsAreLimited())

        <div class="help d-flex justify-content-between">
            <div>Tags are limited in this group, please define the allowed tags bellow</div>
            <a href="?limit_tags=no" class="btn btn-primary">Disable controlled tags</a>
        </div>

        <div>
            @can('manage-tags', $group)
                <div class="toolbox">
                    <a class="btn btn-primary" href="{{ route('groups.tags.create', $group ) }}">
                        <i class="fa fa-plus"></i> {{trans('Add a tag')}}
                    </a>
                </div>
            @endcan
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

    @else
        <div class="help d-flex justify-content-between">
            <div>This group uses free tagging (any tag is allowed to classify content)</div>
            <a href="?limit_tags=yes" class="btn btn-primary">Enable controlled tags</a>
        </div>

        <div>
            Here are the used tags in this group curently :

            @forelse( $tags as $tag )
                @include('tags.tag')
            @empty
                {{trans('messages.nothing_yet')}}
            @endforelse
        </div>

    @endif



@endsection
