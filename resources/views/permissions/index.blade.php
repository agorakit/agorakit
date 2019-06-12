@extends('app')

@section('content')

    @include('groups.tabs')


    <h1>
        @lang('Manage permissions for this group')
    </h1>

    <p class="mb-4">
        @lang('Currently group admins always have all permissions')
    </p>

    {!! Form::model($group, array('action' => ['GroupPermissionController@update', $group])) !!}

    <table class="table table-striped">
        <thead>
            <tr>


                <th></th>
                <th>Member</th>
                <th>Admin</th>
            </tr>
        </thead>
        <tbody>

            <tr>
                <td>
                    Can create discussions
                </td>

                <td>
                    <div class="form-check">
                        {!!Form::checkbox('member[]', 'create-discussion', $member->contains('create-discussion'))!!}
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        {!!Form::checkbox('admin[]', 'create-discussion', $admin->contains('create-discussion'), ['disabled'])!!}
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    Can create files
                </td>

                <td>
                    <div class="form-check">
                        {!!Form::checkbox('member[]', 'create-file', $member->contains('create-file'))!!}
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        {!!Form::checkbox('admin[]', 'create-file', $admin->contains('create-file'), ['disabled'])!!}
                    </div>
                </td>
            </tr>


            <tr>
                <td>
                    Can create actions
                </td>

                <td>
                    <div class="form-check">
                        {!!Form::checkbox('member[]', 'create-action', $member->contains('create-action'))!!}
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        {!!Form::checkbox('admin[]', 'create-action', $admin->contains('create-action'), ['disabled'])!!}
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    Can invite new members
                </td>

                <td>
                    <div class="form-check">
                        {!!Form::checkbox('member[]', 'invite', $member->contains('invite'))!!}
                    </div>
                </td>
                <td>
                    <div class="form-check">
                        {!!Form::checkbox('admin[]', 'invite', $admin->contains('invite'), ['disabled'])!!}
                    </div>
                </td>
            </tr>

        </tbody>

    </table>

    <div class="form-group">
        {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary btn-lg']) !!}
    </div>


    {!! Form::close() !!}




@endsection
