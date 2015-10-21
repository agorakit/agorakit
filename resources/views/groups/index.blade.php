@extends('app')



@section('content')


<div class="container">

  <div class="page-header">
    <h1>{{ trans('messages.groups') }} <a href="{{ action('GroupController@create') }}" class="btn btn-primary"><i class="fa fa-bolt"></i>
      {{ trans('group.create_a_group_button') }}</a></h1>
    </div>

    <p>{{ trans('messages.all_the_groups_welcome') }}</p>
    <div class="groups_scroller">

      <div class="row">
        @forelse( $groups as $group )
        <div class="col-sm-4 col-md-3">
          <div class="thumbnail group">
            <a href="{{ action('GroupController@show', $group->id) }}">
              <img src="http://lorempixel.com/242/150/?id={{$group->id}}"/>
            </a>
            <div class="caption">
              <h4><a href="{{ action('GroupController@show', $group->id) }}">{{ $group->name }}</a></h4>
              <p class="summary">{{ str_limit($group->body, 100) }}</p>
              <p>
                @if ($group->isMember())
                <td><a class="btn btn-default" href="{{ action('MembershipController@leave', $group->id) }}"><i class="fa fa-sign-out"></i>{{ trans('group.leave') }}</a></td>
                @else
                <td><a class="btn btn-primary" href="{{ action('MembershipController@join', $group->id) }}"><i class="fa fa-sign-in"></i>
                  {{ trans('group.join') }}</a></td>
                  @endif
                </p>
              </div>
            </div>
          </div>
          @empty
          {{trans('group.no_group_yet')}}

          @endforelse
        </div>


        {!!$groups->render()!!}
      </div>

    </div>
    @endsection

    @section('footer')

    {!! Html::script('/packages/jscroll/jquery.jscroll.min.js') !!}

    <script>
    $(document).ready(function(){

      //hides the default paginator
      $('ul.pagination:visible:first').hide();

      //init jscroll and tell it a few key configuration details
      //nextSelector - this will look for the automatically created
      //contentSelector - this is the element wrapper which is cloned and appended with new paginated data
      $('div.groups_scroller').jscroll({
        debug: true,
        autoTrigger: true,
        nextSelector: '.pagination li.active + li a',
        contentSelector: 'div.groups_scroller',
        callback: function() {

          //again hide the paginator from view
          $('ul.pagination:visible:first').hide();

        }
      });
    });
    </script>
    @endsection
