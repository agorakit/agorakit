@extends('app')

@section('content')

    {!! Form::open(['action' => 'Admin\SettingsController@update', 'files' => true]) !!}

    <div class="">
        <h1>{{trans('messages.settings')}}</h1>

        <div class="mb-3">
            {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary']) !!}
        </div>

    </div>

    <div>

        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#presentation" type="button"
                    role="tab" aria-controls="home" aria-selected="true">{{trans('messages.appearance')}}</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button"
                    role="tab" aria-controls="profile" aria-selected="false">{{trans('messages.permissions')}}</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button"
                    role="tab" aria-controls="profile" aria-selected="false">{{trans('messages.notifications')}}</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#custom" type="button"
                    role="tab" aria-controls="profile" aria-selected="false">{{trans('messages.custom_content')}}</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#tags" type="button"
                    role="tab" aria-controls="profile" aria-selected="false">{{trans('messages.tags')}}</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#navbar-settings" type="button"
                   role="tab" aria-controls="profile" aria-selected="false">{{trans('messages.navigation')}}</a>
            </li>
        </ul>
    </div>

    <div class="tab-content">

        <div class="tab-pane active" id="presentation" role="tabpanel" aria-labelledby="home-tab" tabindex="0">

            <div class="setting">
                <h2>{{ __('Application name') }}</h2>
                <div class="form-group">
                    <div class="setting-help">
                        {{ __('The name of your application. Used everywhere, in titles, in emails, etc... Choose a short and catchy name') }}
                    </div>
                    {!! Form::text('name', setting('name'), ['class' => 'form-control']) !!}
                </div>
            </div>

            <div class="setting">
                <h2>{{ __('Application logo') }}</h2>
                <div class="form-group">
                    <div class="setting-help">
                        {{ __('This logo will be used in the top left area and as a favicon') }}
                    </div>
                    <input aria-label="{{ trans('messages.select_one_file') }}" name="logo" id="logo" type="file" title="{{ trans('messages.select_one_file') }}">
                </div>
            </div>

            <div class="setting">
                <h2>{{ __('Home page presentation text : for anonymous users') }}
                </h2>
                <div class="form-group">
                    <div class="setting-help">
                        {{ __('This is shown on the homepage for non connected user. Make it attractive :-)') }}
                    </div>

                    <x-setting-localized name="homepage_presentation" />
                </div>
            </div>

            <div class="setting">
                <h2>{!! __('Home page presentation text <em>for logged-in users</em>') !!}
                </h2>
                <div class="setting-help">
                    {{ __('You can use it for announcements for example') }}
                </div>
                <x-setting-localized name="homepage_presentation_for_members" />
            </div>

            <div class="setting">

                <h2>{{ __('Help page') }}</h2>
                <div class="setting-help">
                    {{ __('A single help page you can customize, available on the user menu, for logged in user') }}
                </div>
                <div class="form-group">
                    <x-setting-localized name="help_text" />
                </div>
            </div>
        </div>

        <div class="tab-pane" id="permissions" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
            <div class="setting">

                <h2>{{ __('Group creation') }}</h2>
                <div class="form-group">
                    <div class="space-y-4">
                        <div>
                            {!! Form::checkbox('user_can_create_groups', 'yes', setting('user_can_create_groups', true)) !!}
                            {{ __('Allow regular users to create groups (if you uncheck this box, only admins will be able to create groups)') }}
                        </div>
                        <div>

                            {!! Form::checkbox('user_can_create_secret_groups', 'yes', setting('user_can_create_secret_groups')) !!}
                            {!! __(
                                'Allow regular users to create <strong>secret</strong> groups (if you uncheck this box, only admins will be able to create secret groups)',
                            ) !!}
                        </div>
                        <div>
                            {!! Form::checkbox('user_can_import_groups', 'yes', setting('user_can_import_groups')) !!}
                            {{ __('Allow regular users to import groups (if you uncheck this box, only admins will be able to import groups)') }}
                        </div>
                        <div>

                            {!! Form::checkbox('user_can_register', 'yes', setting('user_can_register', true)) !!}
                            {!! __('Allow anyone to create a new user account on this server (unchecking this box will disable registration)') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane" id="notifications" role="tabpanel" aria-labelledby="messages-tab" tabindex="0">
            <div class="form-group">
                {!! Form::checkbox('notify_admins_on_group_create', 'yes', setting('notify_admins_on_group_create')) !!}
                {{ __('Notify administrators when a new group is created') }}
            </div>
        </div>

        <div class="tab-pane" id="custom" role="tabpanel" aria-labelledby="settings-tab" tabindex="0">
            <h2>{{ __('Custom footer code') }}</h2>
            <div class="form-group">
                <div class="setting-help">
                    {{ __('You can add html / css / js at the footer of each page here') }}
                </div>
                <textarea name="custom_footer" class="form-control">{!! setting('custom_footer') !!}</textarea>

            </div>
        </div>

        <div class="tab-pane" id="tags" role="tabpanel" aria-labelledby="settings-tab" tabindex="0">
            <h2>{{ __('Tags') }}</h2>
            <div class="form-group">
                {{ __('Limit the allowed tags for tagging users') }}.<br />
                <div class="setting-help">
                    {{ __('Enter a coma separated list of allowed tags or leave blank to allow free tagging') }}
                </div>
                <select style="width: 100%" name="user_tags[]" class="js-tags form-control" data-tags="true"
                    multiple="multiple">
                    @if (is_array(setting()->getArray('user_tags')))
                        @foreach (setting()->getArray('user_tags') as $tag)
                            <option value="{{ $tag }}" selected="selected">{{ $tag }}</option>
                        @endforeach
                    @endif
                </select>

            </div>

            <div class="form-group">
                {{ __('Limit the allowed tags for tagging groups') }}.<br />
                <div class="setting-help">
                    {{ __('Enter a coma separated list of allowed tags or leave blank to allow free tagging') }}
                </div>

                <select style="width: 100%" name="group_tags[]" class="js-tags form-control" data-tags="true"
                    multiple="multiple">
                    @if (is_array(setting()->getArray('group_tags')))
                        @foreach (setting()->getArray('group_tags') as $tag)
                            <option value="{{ $tag }}" selected="selected">{{ $tag }}</option>
                        @endforeach
                    @endif
                </select>

            </div>

        </div>

        <div class="tab-pane" id="navbar-settings" role="tabpanel" aria-labelledby="navbar-tab" tabindex="0">
            <h2>{{ __('Navigation bar') }}</h2>
            <div class="form-group">
                {!! Form::checkbox('show_overview_inside_navbar', 'yes', setting('show_overview_inside_navbar', true)) !!}
                {{ __('Show overview button inside the navigation bar') }}
            </div>
            <div id="overview-settings-visibility" style="margin-left: 1em">
                <h3>{{ __('Overview') }}</h3>
                @foreach($overviewItems as $key => $value)
                    <div class="form-group">
                        {!! Form::checkbox($key, 'yes', setting($key, true)) !!}
                        {{ __('Show :name button into overview', ['name' => $value]) }}
                    </div>
                @endforeach
            </div>
            <div class="form-group">
                {!! Form::checkbox('show_help_inside_navbar', 'yes', setting('show_help_inside_navbar', true)) !!}
                {{ __('Show Help button inside the navigation bar') }}
            </div>
            <div class="form-group">
                {!! Form::checkbox('show_locales_inside_navbar', 'yes', setting('show_locales_inside_navbar', true)) !!}
                {{ __('Show Locales button inside the navigation bar') }}
            </div>
            <div class="locale-configuration" style="margin-left: 1em">
                <h3>{{ __('Locales') }}</h3>
                @foreach(config('app.locales') as $locale)
                    <div class="form-group">
                        {!! Form::checkbox("show_locale_{$locale}", 'yes', setting("show_locale_{$locale}", true)) !!}
                        {{ __("Show :locale button into locales", ['locale' => $locale]) }}
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-3">
            {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary']) !!}
        </div>

        {!! Form::close() !!}

    </div>

@endsection
