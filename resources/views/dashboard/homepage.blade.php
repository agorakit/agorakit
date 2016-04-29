@extends('app')

@section('content')

  <div class="page_header">
    <h1><i class="fa fa-home"></i>
      {{Config::get('mobilizator.name')}}
    </h1>
  </div>


  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">

    <li role="presentation" @unless (Auth::check()) class="active" @endunless>
      <a href="#presentation" aria-controls="presentation" role="tab" data-toggle="tab">
        <i class="fa fa-info-circle"></i>
        <span class="hidden-xs">{{ trans('messages.presentation') }}</span>
      </a>
    </li>

    <li role="presentation" @if (Auth::check()) class="active" @endif>
      <a href="#discussions" aria-controls="discussions" role="tab" data-toggle="tab">
        <i class="fa fa-comments"></i>
        <span class="hidden-xs">{{ trans('messages.latest_discussions') }}</span>
      </a>
    </li>
    <li role="presentation">
      <a href="#actions" aria-controls="actions" role="tab" data-toggle="tab">
        <i class="fa fa-calendar"></i>
        <span class="hidden-xs">{{ trans('group.latest_actions') }}</span>
      </a>
    </li>

    @if ($my_groups)
      <li role="presentation">
        <a href="#mygroups" aria-controls="groups" role="tab" data-toggle="tab">
          <i class="fa fa-cube"></i>
          <span class="hidden-xs">{{ trans('messages.my_groups') }}</span>
        </a>
      </li>
    @endif

    <li role="presentation">
      <a href="#groups" aria-controls="groups" role="tab" data-toggle="tab">
        <i class="fa fa-cubes"></i>
        <span class="hidden-xs">{{ trans('messages.all_groups') }}</span>
      </a>
    </li>

  </ul>

  <div class="tab_content"

  <!-- Tab panes -->
  <div class="tab-content">


    <div role="tabpanel" class="tab-pane @unless (Auth::check()) active @endunless" id="presentation">

        @if (Auth::check())
          <a class="btn btn-primary btn-xs" href="{{action('SettingsController@settings')}}">
            <i class="fa fa-pencil"></i> {{trans('messages.modify_intro_text')}}
          </a>
        @endif
  
      {!! setting('homepage_presentation', trans('documentation.intro')) !!}



    </div>



    <div role="tabpanel" class="tab-pane @if (Auth::check()) active @endif" id="discussions">
      <h1>{{ trans('messages.latest_discussions') }}</h1>

      @if (Auth::check() && Auth::user()->getPreference('show', 'all') == 'all')

        {{ trans('messages.you_see_all') }}
        <a class="btn btn-default btn-sm" href="{{action('DashboardController@index')}}?show=my">{{ trans('messages.show_only_my_groups') }}</a>
      @endif

      @if (Auth::check() && Auth::user()->getPreference('show', 'all') == 'my')
        {{ trans('messages.you_see_only_your_stuff') }}
        <a class="btn btn-default btn-sm" href="{{action('DashboardController@index')}}?show=all">{{ trans('messages.show_all') }}</a>
      @endif

      <table class="table table-hover special">
        <thead>
          <tr>
            <th style="width: 75%">{{ trans('messages.title') }}</th>
            <th>{{ trans('messages.date') }}</th>
            <th>{{ trans('messages.to_read') }}</th>
          </tr>
        </thead>

        <tbody>
          @foreach( $all_discussions as $discussion )
            <tr>
              <td class="content">
                <a href="{{ action('DiscussionController@show', [$discussion->group_id, $discussion->id]) }}">
                  <span class="name">{{ $discussion->name }}</span>
                  <span class="summary">{{ summary($discussion->body) }}</span>
                </a>
                <br/>
                <span class="group-name"><a href="{{ action('GroupController@show', [$discussion->group_id]) }}">{{ $discussion->group->name }}</a></span>
              </td>

              <td>
                {{ $discussion->updated_at->diffForHumans() }}
              </td>

              <td>
                @if ($discussion->unReadCount() > 0)
                  <i class="fa fa-comment"></i>
                  <span class="badge">{{ $discussion->unReadCount() }}</span>
                @endif
              </td>

            </tr>
          @endforeach

        </tbody>
      </table>
    </div>


    <div role="tabpanel" class="tab-pane" id="actions">
      <h1>{{ trans('group.latest_actions') }}</h1>

      @if (Auth::check() && Auth::user()->getPreference('show', 'all') == 'all')
        {{ trans('messages.you_see_all') }}
        <a class="btn btn-default btn-sm" href="{{action('DashboardController@index')}}?show=my">{{ trans('messages.show_only_my_groups') }}</a>
      @endif

      @if (Auth::check() && Auth::user()->getPreference('show', 'all') == 'my')
        {{ trans('messages.you_see_only_your_stuff') }}
        <a class="btn btn-default btn-sm" href="{{action('DashboardController@index')}}?show=all">{{ trans('messages.show_all') }}</a>
      @endif

      <table class="table table-hover special">
        <thead>
          <tr>
            <th style="width: 50%">{{ trans('messages.title') }}</th>
            <th>{{ trans('messages.date') }}</th>
            <th>{{ trans('messages.where') }}</th>
          </tr>
        </thead>

        <tbody>
          @foreach( $all_actions as $action )
            <tr>
              <td class="content">
                <a href="{{ action('ActionController@show', [$action->group_id, $action->id]) }}">
                  <span class="name">{{ $action->name }}</span>
                  <span class="summary">{{ summary($action->body) }}</span>
                </a>
                <br/>
                <span class="group-name"><a href="{{ action('GroupController@show', [$action->group_id]) }}">{{ $action->group->name }}</a></span>
              </td>

              <td>
                {{$action->start->format('d/m/Y H:i')}}
              </td>

              <td class="content">
                {{$action->location}}
              </td>
            </tr>
          @endforeach

        </tbody>
      </table>

    </div>

    @if ($my_groups)
      <div role="tabpanel" class="tab-pane" id="mygroups">

        <h1>{{ trans('messages.my_groups') }}</h1>

        <div class="row">
          @forelse( $my_groups as $group )

            <div class="col-xs-6 col-md-3">
              <div class="thumbnail group">
                <a href="{{ action('GroupController@show', $group->id) }}">
                  <img src="{{action('GroupController@cover', $group->id)}}"/>
                </a>
                <div class="caption">
                  <h4><a href="{{ action('GroupController@show', $group->id) }}">{{ $group->name }}</a></h4>
                  <p class="summary">{{ summary($group->body, 150) }}</p>
                  <p>




                    <a class="btn btn-primary" href="{{ action('MembershipController@leave', $group->id) }}"><i class="fa fa-sign-out"></i>
                      {{ trans('group.leave') }}</a>


                      <a class="btn btn-primary" href="{{ action('GroupController@show', $group->id) }}">{{ trans('group.visit') }}</a>

                    </p>
                  </div>
                </div>
              </div>

            @empty
              <p>{{trans('group.no_group_joined_yet')}}</p>
            @endforelse
          </div>

        </div>
      @endif




      <div role="tabpanel" class="tab-pane" id="groups">

        <h1>{{ trans('messages.all_groups') }}</h1>


        <div class="row">
          @forelse( $groups as $group )
            <div class="col-xs-6 col-md-3">
              <div class="thumbnail group">
                <a href="{{ action('GroupController@show', $group->id) }}">
                  <img src="{{action('GroupController@cover', $group->id)}}"/>
                </a>
                <div class="caption">
                  <h4><a href="{{ action('GroupController@show', $group->id) }}">{{ $group->name }}</a></h4>
                  <p class="summary">{{ summary($group->body, 150) }}</p>
                  <p>



                    @unless ($group->isMember())
                      <a class="btn btn-primary" href="{{ action('MembershipController@join', $group->id) }}"><i class="fa fa-sign-in"></i>
                        {{ trans('group.join') }}</a>
                      @endunless

                      <a class="btn btn-primary" href="{{ action('GroupController@show', $group->id) }}">{{ trans('group.visit') }}</a>

                    </p>
                  </div>
                </div>
              </div>
            @empty
              {{trans('group.no_group_yet')}}
        @endforelse

        <div class="col-xs-6 col-md-3">
          <div class="thumbnail group">
            <a href="{{ action('GroupController@create') }}">
              <div style="margin: auto; text-align: center; width: 100%; height:auto;"><i class="fa fa-plus-circle" style="font-size: 150px;" aria-hidden="true"></i></div>

            </a>
            <div class="caption">
              <h4><a href="{{ action('GroupController@create') }}">{{ trans('group.your_group_here') }}</a></h4>
              <p class="summary">{{ trans('group.create_a_group_intro') }}</p>
              <p>

                  <a class="btn btn-primary" href="{{ action('GroupController@create') }}">{{ trans('group.create') }}</a>

                </p>
              </div>
            </div>
          </div>

      </div>

    </div>


  </div>
@endsection

@section('footer')

@endsection
