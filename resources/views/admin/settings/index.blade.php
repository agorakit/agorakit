@extends('app')

@section('content')

    <h1> Settings</h1>

    {!! Form::open(['action' => 'Admin\SettingsController@update']) !!}

    <div class="alert alert-info">This admin setting page is a work in progress. Some settings are not used yet (but all are saved for future use)</div>

    <h2>Home page presentation text : for anonymous users</h2>
    <div class="form-group">
        {!! Form::textarea('homepage_presentation', setting('homepage_presentation'), ['class' => 'form-control wysiwyg']) !!}
    </div>

    <h2>Home page presentation text : for logged-in users</h2>
    <p>You can use it for announcements for example</p>
    <div class="form-group">
        {!! Form::textarea('homepage_presentation_for_members', setting('homepage_presentation_for_members'), ['class' => 'form-control wysiwyg']) !!}
    </div>

    <h2>Help page</h2>
    <p>A single help page you can customize, available on the user menu, for logged in user</p>
    <div class="form-group">
        {!! Form::textarea('help_text', setting('help_text'), ['class' => 'form-control wysiwyg']) !!}
    </div>


    <h2>Group creation</h2>
    <div class="form-group">
        {!! Form::checkbox('user_can_create_groups',1 , setting('user_can_create_groups')) !!}
        Allow regular users to create groups (if you uncheck this box, only admins will be able to create groups)
    </div>

    <div class="form-group">
        {!! Form::checkbox('notify_admins_on_group_create',1 , setting('notify_admins_on_group_create')) !!}
        Notify administrators when a new group is created
    </div>



    <div class="form-group">
        {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-lg btn-primary']) !!}
    </div>


    {!! Form::close() !!}


</div>

@endsection


@include('partials.wysiwyg')
