@extends('app')

@include('partials.datatables')



@section('content')

    @include('groups.tabs')


    <div class="tab_content">


        <h2>{{trans('messages.files_in_this_group')}}

            @can('create-file', $group)
                <a class="btn btn-primary btn-xs" href="{{ route('groups.files.create', $group->id ) }}">
                    <i class="fa fa-plus"></i>
                    {{trans('messages.create_file_button')}}
                </a>
            @endcan

        </h2>


            <table class="table table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th>{{trans('messages.name')}}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse( $files as $file )
                        <td>
                            <a href="{{ route('groups.files.show', [$group->id, $file->id]) }}"><img src="{{ route('groups.files.thumbnail', [$group->id, $file->id]) }}"/></a>
                        </td>

                        <td>
                            <div class="ellipsis" style="max-width: 30em">
                                <a  href="{{ route('groups.files.show', [$group->id, $file->id]) }}">{{ $file->name }}</a>
                            </div>
                        </td>



                        <td>
                            @can('edit', $file)
                                <a class="btn btn-primary btn-xs" href="{{ route('groups.files.edit', [$group->id, $file->id]) }}"><i class="fa fa-edit"></i>
                                    {{trans('messages.edit')}}</a>
                                @endcan

                                @can('delete', $file)
                                    <a class="btn btn-warning btn-xs" href="{{ route('groups.files.deleteconfirm', [$group->id, $file->id]) }}"><i class="fa fa-trash"></i>
                                        {{trans('messages.delete')}}</a>
                                    @endcan

                                </td>

                                <td>
                                    @unless (is_null ($file->user))
                                        <a href="{{ route('users.show', $file->user->id) }}">{{ $file->user->name }}</a>
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




            </div>


        @endsection
