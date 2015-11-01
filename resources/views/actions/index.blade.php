@extends('app')


@section('footer')
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
{!! $calendar->script() !!}

@stop

@section('content')

@include('partials.grouptab')

<div class="container">
  <h2>Agenda and actions of this group <a class="btn btn-primary btn-xs" href="{{ action('ActionController@create', $group->id ) }}"><i class="fa fa-plus"></i> {{trans('action.create_one_button')}}</a></h2>

  <div class="spacer"></div>


  {!! $calendar->calendar() !!}




  <table class="table table-hover">
    <tr>
      <th>Name</th>
      <th>Starts</th>
      <th>Stops</th>
      <th>Location</th>
      <th>Author</th>
      <th>Posted</th>
    </tr>
    @foreach( $actions as $action )
    <tr>
      <td>
        <a href="{{ action('ActionController@show', [$group->id, $action->id]) }}">{{ $action->name }}</a>
      </td>

      <td>
        <a href="{{ action('ActionController@show', [$group->id, $action->id]) }}">{{ $action->start }}</a>
      </td>

      <td>
        <a href="{{ action('ActionController@show', [$group->id, $action->id]) }}">{{ $action->stop }}</a>
      </td>

      <td>
        <a href="{{ action('ActionController@show', [$group->id, $action->id]) }}">{{ $action->location }}</a>
      </td>

      <td>
        @unless (is_null ($action->user))
        <a href="{{ action('UserController@show', $action->user->id) }}">{{ $action->user->name }}</a>
        @endunless
      </td>

      <td>
        <a href="{{ action('ActionController@show', [$group->id, $action->id]) }}">{{ $action->updated_at->diffForHumans() }}</a>
      </td>

    </tr>
    @endforeach
  </table>




</div>




@endsection
