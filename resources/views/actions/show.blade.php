@extends('app')

@section('content')

@include('partials.grouptab')
<div class="tab_content">

  <div class="action">
    <h2 class="name">
      <a href="{{ action('ActionController@index', [$group->id]) }}">{{trans('messages.actions')}}</a> /
      {{ $action->name }} <a href="{{ action('ActionController@edit', [$group->id, $action->id]) }}" class="btn btn-primary btn-xs">{{trans('messages.edit')}}</a>
    </h2>

    <div class="meta">{{trans('messages.started_by')}} <span class="user">{{ $action->user->name}}</span>, in {{ $action->group->name}} {{ $action->created_at->diffForHumans()}} </div>

    <h4>{{trans('messages.what')}} ?</h4>

    <p class="body">
      {{ $action->body }}
    </p>

    <h4>{{trans('messages.when')}} ?</h4>
    <p>{{trans('messages.begins')}} : {{$action->start->format('d/m/Y H:i')}}</p>
    <p>{{trans('messages.ends')}} : {{$action->stop->format('d/m/Y H:i')}}</p>

    <h4>{{trans('messages.where')}} ?</h4>
    <p>{{$action->location}}</p>

  </div>

</div>


@endsection
