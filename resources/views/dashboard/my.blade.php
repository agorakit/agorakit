@extends('app')

@section('content')

  <div class="page_header">
    <h2><i class="fa fa-home"></i>
      {{Config::get('mobilizator.name')}}
    </h2>
  </div>



  <div class="tab_content">

    <div class="menu">
      This is a test for the future menu system :
      {!! Menu::get('navbar')->asUl() !!}
    </div>

    <div class="intro">

      <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#intro" aria-expanded="false" aria-controls="collapseExample">
        ?
      </button>

      <div class="collapse" id="intro">
        {!! setting('homepage_presentation', trans('documentation.intro')) !!}

        @if (Auth::check())
          <a class="btn btn-primary btn-xs" href="{{action('SettingsController@settings')}}">
            <i class="fa fa-pencil"></i> {{trans('messages.modify_intro_text')}}
          </a>
        @endif
      </div>
    </div>



    <div class="row">
      <div class="col-md-6">
        <h2>{{ trans('messages.latest_discussions_my') }}</h2>

        <table class="table table-hover special">
          <thead>
            <tr>
              <th style="width: 75%">{{ trans('messages.title') }}</th>
              <th>{{ trans('messages.date') }}</th>
              <th>{{ trans('messages.to_read') }}</th>
            </tr>
          </thead>

          <tbody>
            @foreach( $my_discussions as $discussion )
              <tr>
                <td class="content">
                  <a href="{{ action('DiscussionController@show', [$discussion->group_id, $discussion->id]) }}">
                    <span class="name">{{ $discussion->name }}</span>
                    <span class="summary">{{ summary($discussion->body) }}</span>
                  </a>
                  <br/>
                  <span class="group-name"><a href="{{ action('GroupController@show', [$discussion->group_id]) }}">{{ $discussion->group->name }}</a></span>
                </td>

                <td class="small">
                  {{ $discussion->updated_at->diffForHumans() }}
                </td>

                <td class="small">
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

      <div class="col-md-6">
        <h2>{{ trans('messages.agenda_my') }}</h2>


        <table class="table table-hover special">
          <thead>
            <tr>
              <th style="width: 50%">{{ trans('messages.title') }}</th>
              <th>{{ trans('messages.date') }}</th>
              <th>{{ trans('messages.where') }}</th>
            </tr>
          </thead>

          <tbody>
            @foreach( $my_actions as $action )
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

    </div>



    <div class="row">

      <div class="col-md-6">
        <h2>{{ trans('messages.latest_discussions_others') }}</h2>
        <table class="table table-hover special">
          <thead>
            <tr>
              <th style="width: 75%">{{ trans('messages.title') }}</th>
              <th>{{ trans('messages.date') }}</th>
              <th>{{ trans('messages.to_read') }}</th>
            </tr>
          </thead>

          <tbody>
            @foreach( $other_discussions as $discussion )
              <tr>
                <td class="content">
                  <a href="{{ action('DiscussionController@show', [$discussion->group_id, $discussion->id]) }}">
                    <span class="name">{{ $discussion->name }}</span>
                    <span class="summary">{{ summary($discussion->body) }}</span>
                  </a>
                  <br/>
                  <span class="group-name"><a href="{{ action('GroupController@show', [$discussion->group_id]) }}">{{ $discussion->group->name }}</a></span>
                </td>

                <td class="small">
                  {{ $discussion->updated_at->diffForHumans() }}
                </td>

                <td class="small">
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







      <div class="col-md-6">

        <h2>{{ trans('messages.agenda_others') }}</h2>


        <table class="table table-hover special">
          <thead>
            <tr>
              <th style="width: 50%">{{ trans('messages.title') }}</th>
              <th>{{ trans('messages.date') }}</th>
              <th>{{ trans('messages.where') }}</th>
            </tr>
          </thead>

          <tbody>
            @foreach( $other_actions as $action )
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
    </div>

  </div>



@endsection
