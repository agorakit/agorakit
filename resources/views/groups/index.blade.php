@extends('app')



@section('content')


<div class="page_header">
  <h1>{{ trans('messages.groups') }}</h1>
</div>

<div class="groups_scroller">

  <div class="row">
    @forelse( $groups as $group )
    <div class="col-sm-4 col-md-3">
      <div class="thumbnail group">
        <a href="{{ action('GroupController@show', $group->id) }}">
          <img src="{{action('GroupController@cover', $group->id)}}"/>
        </a>
        <div class="caption">
          <h4><a href="{{ action('GroupController@show', $group->id) }}">{{ $group->name }}</a></h4>
          <p class="summary">{{ str_limit(strip_tags($group->body), 300) }}</p>
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


    {!!$groups->render()!!}
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
