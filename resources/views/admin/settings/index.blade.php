@extends('app')

@section('content')


    <h1>Settings</h1>

    {!! Form::open(['action' => 'Admin\SettingsController@update', 'files' => true]) !!}

    <div class="alert alert-info">This admin setting page is a work in progress. Some settings are not used yet (but all are saved for future use)</div>

    <div class="setting">
        <h2>Application name</h2>
        <div class="form-group">
            <div class="setting-help">
                The name of your application. Used everywhere, in titles, in emails, etc... Choose a short and catchy name
            </div>
            {!! Form::text('name', setting('name'), ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="setting">
        <h2>Application logo</h2>
        <div class="form-group">
            <div class="setting-help">
                This logo will be used in the top left area and as a favicon
            </div>
            <input name="logo" id="logo" type="file" title="{{trans('messages.select_one_file')}}">
        </div>
    </div>

    <div class="setting">
        <h2>Home page presentation text : for anonymous users</h2>
        <div class="form-group">
            <div class="setting-help">
                This is shown on the homepage for non connected user. Make it attractive :-)
            </div>
            {!! Form::textarea('homepage_presentation', setting('homepage_presentation'), ['class' => 'form-control wysiwyg']) !!}
        </div>
    </div>

    <div class="setting">
        <h2>Home page presentation text <em>for logged-in users</em></h2>
        <div class="setting-help">
            You can use it for announcements for example
        </div>
        <div class="form-group">
            {!! Form::textarea('homepage_presentation_for_members', setting('homepage_presentation_for_members'), ['class' => 'form-control wysiwyg']) !!}
        </div>
    </div>

    <div class="setting">

        <h2>Help page</h2>
        <div class="setting-help">
            A single help page you can customize, available on the user menu, for logged in user
        </div>
        <div class="form-group">
            {!! Form::textarea('help_text', setting('help_text'), ['class' => 'form-control wysiwyg']) !!}
        </div>
    </div>

    <div class="setting">

        <h2>Group creation</h2>
        <div class="form-group">
            {!! Form::checkbox('user_can_create_groups',1 , setting('user_can_create_groups')) !!}
            Allow regular users to create groups (if you uncheck this box, only admins will be able to create groups)
        </div>

        <div class="form-group">
            {!! Form::checkbox('user_can_create_secret_groups',0 , setting('user_can_create_secret_groups')) !!}
            Allow regular users to create <strong>secret</strong> groups (if you uncheck this box, only admins will be able to create secret groups)
        </div>

        <div class="form-group">
            {!! Form::checkbox('notify_admins_on_group_create',1 , setting('notify_admins_on_group_create')) !!}
            Notify administrators when a new group is created
        </div>
    </div>


    <div class="setting">

        <h2>Post by email</h2>
        <div class="form-group">
            {!! Form::checkbox('user_can_post_by_email',1 , setting('user_can_post_by_email')) !!}
            Allow users to post by email
        </div>

        <div class="form-group">
            Mail server (pop3) :
            {!! Form::text('mail_server', setting('mail_server'), ['class' => 'form-control']) !!}
            <br/>
            Login :
            {!! Form::text('mail_login', setting('mail_login'), ['class' => 'form-control']) !!}
            <br/>
            Password :
            {!! Form::text('mail_password', setting('mail_password'), ['class' => 'form-control']) !!}

        </div>


    </div>


    <div class="form-group">
        {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-lg btn-primary']) !!}
    </div>


    {!! Form::close() !!}


</div>

@endsection


@include('partials.wysiwyg')
