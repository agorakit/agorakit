@extends('app')



@section('content')

    @include('groups.tabs')

    <h1> Usage of tags in this group</h1>



    @if ($group->tagsAreLimited())
        <h3>
            <div class="mb-4 mt-2">
                <i class="far fa-check-square"></i> Use controlled tags in this group (please define the allowed tags bellow)
                <br/>
                <a href="?limit_tags=no"  up-target=".tab_content"> <i class="far fa-square"></i> Allow any tags in this group to classify content</a>
            </div>

        </h3>
        <div>
            @can('manage-tags', $group)
                <div class="toolbox mb-2">
                    <a up-modal=".dialog" class="btn btn-primary" href="{{ route('groups.tags.create', $group ) }}">
                        <i class="fa fa-plus"></i> {{trans('Add a tag')}}
                    </a>
                </div>
            @endcan
        </div>




        <div class="tags items">
            <table class="table table-sm">
                @forelse( $tags as $tag )
                    <tr>
                        <td>
                            <h2>@include('tags.tag')</h2>
                        </td>
                        <td>
                            <a up-modal=".dialog" class="btn btn-primary" href="{{ route('groups.tags.edit', [$group, $tag] ) }}">
                                <i class="fas fa-edit"></i>
                                @lang('Edit')
                            </a>
                            <a up-modal=".dialog" class="btn btn-warning" href="{{ route('groups.tags.deleteconfirm', [$group, $tag] ) }}">
                                <i class="fas fa-trash"></i>
                                @lang('Remove')
                            </a>
                        </td>

                    </tr>
                @empty
                    <div class="alert alert-danger">
                        Currently your group doesn't allow any tags. You must either enable free tagging or define some allowed tags above.
                    </div>
                @endforelse
            </table>


        </div>

    @else
        <h3>
            <div class="mb-4 mt-2">
                <a href="?limit_tags=yes" up-target=".tab_content"> <i class="far fa-square"></i> Use controlled tags in this group (please define the allowed tags bellow)</a>
                <br/>
                <i class="far fa-check-square"></i> Allow any tags in this group to classify content
            </div>

        </h3>

        <div>
            Here are the used tags in this group curently :

            @forelse( $tags as $tag )
                @include('tags.tag')
            @empty
                currently there are no tags in use in your group. Might be a good time to start tagging your content :-)
            @endforelse
        </div>

    @endif



@endsection
