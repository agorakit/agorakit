@extends('app')

@section('content')

    @include('groups.tabs')


    <div class="toolbox d-md-flex">

        <div>
            @lang('Manage permissions for this group.')

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




    <table class="table">

        <tr>
            <td>Permission</td>
            <td>Member</td>
            <td>Admin</td>
        </tr>

        <tr>
            <td>
                Create discussion :
            </td>
            <td>
                {{in_array('create-discussion', $permissions['member'])}}
            </td>
        </tr>

    </table>





@endsection
