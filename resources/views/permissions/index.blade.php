@extends('app')

@section('js')
    <script>

    $('#custom_permissions').change(function() {
        if($(this).is(":checked")) {
            $( ".permissions" ).show();
        }
        else {
            $( ".permissions" ).hide();
        }
    });
    </script>
@endsection

@section('content')

    @include('groups.tabs')


    <h1>
        @lang('Manage permissions for this group')
    </h1>

    <p class="mb-4">
        @lang('Currently group admins always have all permissions')
    </p>

    {!! Form::model($group, array('action' => ['GroupPermissionController@update', $group])) !!}

    <div class="form-check mb-3">
        {!!Form::checkbox('custom_permissions', 'yes', $custom_permissions, ['id' => 'custom_permissions'])!!}
        Enable custom permissions for this group
    </div>

    <div class="permissions" @unless ($custom_permissions) style="display: none" @endunless>
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
                            {!!Form::checkbox('member-create-discussion', 'yes', $member->contains('create-discussion'))!!}
                        </div>
                    </td>
                    <td>
                        <div class="form-check">
                            {!!Form::checkbox('admin-create-discussion', 'yes', $admin->contains('create-discussion'), ['disabled'])!!}
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        Can create files
                    </td>

                    <td>
                        <div class="form-check">
                            {!!Form::checkbox('member-create-file', 'yes', $member->contains('create-file'))!!}
                        </div>
                    </td>
                    <td>
                        <div class="form-check">
                            {!!Form::checkbox('admin-create-file', 'yes', $admin->contains('create-file'), ['disabled'])!!}
                        </div>
                    </td>
                </tr>


                <tr>
                    <td>
                        Can create events
                    </td>

                    <td>
                        <div class="form-check">
                            {!!Form::checkbox('member-create-event', 'yes', $member->contains('create-event'))!!}
                        </div>
                    </td>
                    <td>
                        <div class="form-check">
                            {!!Form::checkbox('admin-create-event', 'yes', $admin->contains('create-event'), ['disabled'])!!}
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        Can invite new members
                    </td>

                    <td>
                        <div class="form-check">
                            {!!Form::checkbox('member-invite', 'yes', $member->contains('invite'))!!}
                        </div>
                    </td>
                    <td>
                        <div class="form-check">
                            {!!Form::checkbox('admin-invite', 'yes', $admin->contains('invite'), ['disabled'])!!}
                        </div>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

    <div class="form-group">
        {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary']) !!}
    </div>


    {!! Form::close() !!}




@endsection
