@extends('app')



@section('content')


@if(Auth::check())

<div class="page_header">

  <div class="row">

    <div class="col-md-9">
      <h1>{{ trans('messages.latest_discussions') }}</h1>
      <table class="table table-hover special">
        <thead>
          <tr>
            <th style="width: 75%">Titre</th>
            <th>Date</th>
            <th>A lire</th>
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





      <h1>{{ trans('group.latest_actions') }}</h1>
      <table class="table table-hover special">
        <thead>
          <tr>
            <th style="width: 50%">Titre</th>
            <th>Date</th>
            <th>Où</th>
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

    <div class="col-md-3">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{ trans('messages.my_groups') }}</h3>
        </div>
        <div class="panel-body">
            @forelse( $my_groups as $group )
            - <a href="{{ action('GroupController@show', $group->id) }}">{{ $group->name }}</a>
            <br/>
            @empty
            {{trans('group.no_group_joined_yet_act_now')}}
            @endforelse
        </div>
      </div>

    </div>
  </div>
</div>
@endif








<div class="page_header">
  <h1>{{ trans('messages.groups') }}</h1>
  <p>{{ trans('documentation.intro') }}</p>
</div>

<div class="groups_scroller">

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
      <a href="{{ action('GroupController@create') }}" class="btn btn-primary">
        <i class="fa fa-bolt"></i>
        {{ trans('group.create_a_group_button') }}
      </a>

      @endforelse
    </div>

  </div>
  @endsection
