@extends('app')

@section('content')




  {!! Form::open(['action' => 'Admin\SettingsController@update', 'files' => true]) !!}

  <div class="d-flex justify-content-between">
    <h1>Admin Settings</h1>


    <div class="form-group">
      {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-lg btn-primary']) !!}
    </div>

  </div>

  <div class="row">
    <div class="col-3">
      <div class="nav flex-column nav-pills"  role="tablist" aria-orientation="vertical">
        <a class="nav-link active"  data-toggle="pill" href="#presentation" role="tab"  aria-selected="true">Presentation & logo</a>
        <a class="nav-link" data-toggle="pill" href="#permissions" role="tab">Permissions</a>
        <a class="nav-link"  data-toggle="pill" href="#notifications" role="tab">Notifications</a>
        <a class="nav-link"  data-toggle="pill" href="#mail" role="tab">Post by mail</a>
      </div>
    </div>


    <div class="col-9">
      <div class="tab-content" id="v-pills-tabContent">

        <div class="tab-pane fade show active" id="presentation">
          <div class="setting">
            <h2>{{ __('Application name')}}</h2>
            <div class="form-group">
              <div class="setting-help">
                {{ __('The name of your application. Used everywhere, in titles, in emails, etc... Choose a short and catchy name')}}
              </div>
              {!! Form::text('name', setting('name'), ['class' => 'form-control']) !!}
            </div>
          </div>

          <div class="setting">
            <h2>{{ __('Application logo')}}</h2>
            <div class="form-group">
              <div class="setting-help">
                {{ __('This logo will be used in the top left area and as a favicon')}}
              </div>
              <input name="logo" id="logo" type="file" title="{{trans('messages.select_one_file')}}">
            </div>
          </div>

          <div class="setting">
            <h2>{{ __('Home page presentation text : for anonymous users')}}</h2>
            <div class="form-group">
              <div class="setting-help">
                {{ __('This is shown on the homepage for non connected user. Make it attractive :-)')}}
              </div>
              {!! Form::textarea('homepage_presentation', setting('homepage_presentation'), ['class' => 'form-control wysiwyg']) !!}
            </div>
          </div>

          <div class="setting">
            <h2>{{ __('Home page presentation text <em>for logged-in users</em>')}}</h2>
            <div class="setting-help">
              {{ __('You can use it for announcements for example')}}
            </div>
            <div class="form-group">
              {!! Form::textarea('homepage_presentation_for_members', setting('homepage_presentation_for_members'), ['class' => 'form-control wysiwyg']) !!}
            </div>
          </div>

          <div class="setting">

            <h2>{{ __('Help page')}}</h2>
            <div class="setting-help">
              {{ __('A single help page you can customize, available on the user menu, for logged in user')}}
            </div>
            <div class="form-group">
              {!! Form::textarea('help_text', setting('help_text'), ['class' => 'form-control wysiwyg']) !!}
            </div>
          </div>
        </div>


        <div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="v-pills-profile-tab">

          <div class="setting">

            <h2>{{ __('Group creation')}}</h2>
            <div class="form-group">
              {!! Form::checkbox('user_can_create_groups', 'yes' , setting('user_can_create_groups')) !!}
              {{ __('Allow regular users to create groups (if you uncheck this box, only admins will be able to create groups)')}}

              <br/>

              {!! Form::checkbox('user_can_create_secret_groups','yes' , setting('user_can_create_secret_groups')) !!}
              {!! __('Allow regular users to create <strong>secret</strong> groups (if you uncheck this box, only admins will be able to create secret groups)')!!}
            </div>


          </div>


        </div>

        <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="v-pills-messages-tab">
          <div class="form-group">
            {!! Form::checkbox('notify_admins_on_group_create','yes' , setting('notify_admins_on_group_create')) !!}
            {{ __('Notify administrators when a new group is created')}}
          </div>
        </div>


        <div class="tab-pane fade" id="mail" role="tabpanel" aria-labelledby="v-pills-messages-tab">


          <div class="setting">

            <h2>{{ __('Post by email')}}</h2>



            <div class="alert alert-info" role="alert">
              {{ __('If this is enabled, user will be able to post to your groups using a predefined email address.
                Each group received an email address based on it\'s name')}}
                <br/>
                <a href="https://dos.agorakit.org">Read the docs</a> to understand this feature and how to configure it.
              </div>

              <div class="form-group">
                {!! Form::checkbox('user_can_post_by_email',1 , setting('user_can_post_by_email')) !!}
                {{ __('Allow users to post by email')}}
              </div>

              <div class="form-group">

                Email prefix (this is the part before the group name in email). Leave it blank if you use a catch all email:
                {!! Form::text('mail_prefix', setting('mail_prefix'), ['class' => 'form-control']) !!}

                <br/>
                Email suffix (this is the part after the group name in email):
                {!! Form::text('mail_suffix', setting('mail_suffix'), ['class' => 'form-control']) !!}
                <br/>
                Mail server (pop3) :
                {!! Form::text('mail_server', setting('mail_server'), ['class' => 'form-control']) !!}
                <br/>
                Login :
                {!! Form::text('mail_login', setting('mail_login'), ['class' => 'form-control']) !!}
                <br/>
                Password (fill this field to change the password, leave blank otherwise):
                {!! Form::password('mail_password',  ['class' => 'form-control']) !!}

              </div>

            </div>


            <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">...</div>
          </div>
        </div>

        <div class="form-group">
          {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-lg btn-primary']) !!}
        </div>


      </div>


    </div>




    {!! Form::close() !!}


  </div>

@endsection
