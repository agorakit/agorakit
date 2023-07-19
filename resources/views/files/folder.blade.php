@extends('groups.container')

@section('group')
@include('partials.datatables')

        <h2>{{trans('messages.files_in_this_group')}}

            @can('create-file', $group)
                <a class="btn btn-primary btn-xs" href="{{ route('groups.files.create', $group ) }}">
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
                            <a up-follow href="{{ route('groups.files.show', [$group, $file]) }}"><img src="{{ route('groups.files.thumbnail', [$group, $file]) }}"/></a>
                        </td>

                        <td>
                            <div class="ellipsis" style="max-width: 30em">
                                <a  href="{{ route('groups.files.show', [$group, $file]) }}">{{ $file->name }}</a>
                            </div>
                        </td>



                        <td>
                            @can('edit', $file)
                                <a class="btn btn-primary btn-xs" href="{{ route('groups.files.edit', [$group, $file]) }}"><i class="fa fa-edit"></i>
                                    {{trans('messages.edit')}}</a>
                                @endcan

                                @can('delete', $file)
                                    <a class="btn btn-warning btn-xs" href="{{ route('groups.files.deleteconfirm', [$group, $file]) }}"><i class="fa fa-trash"></i>
                                        {{trans('messages.delete')}}</a>
                                    @endcan

                                </td>

                                <td>
                                    @unless (is_null ($file->user))
                                        <a up-follow href="{{ route('users.show', $file->user) }}">{{ $file->user->name }}</a>
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
