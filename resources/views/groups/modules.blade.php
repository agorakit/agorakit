@extends('app')

@section('content')

  @include('groups.tabs')

  <h1>{{ trans('group.features') }}</h1>

  <p>{{ trans('group.features_help') }}</p>


  {!! Form::open(array('action' => ['ModuleController@update', $group])) !!}





  <div class="mb-2">
    @if ($group->getSetting('module_discussion', true) == true)
      <input type="checkbox" name="module_discussion" checked>
    @else
      <input type="checkbox" name="module_discussion" >
    @endif
    Discussions
  </div>

  <div class="mb-2">
    @if ($group->getSetting('module_action', true) == true)
      <input type="checkbox" name="module_action" checked>
    @else
      <input type="checkbox" name="module_action" >
    @endif
    Calendar
  </div>


  <div class="mb-2">
    @if ($group->getSetting('module_file', true) == true)
      <input type="checkbox" name="module_file" checked>
    @else
      <input type="checkbox" name="module_file" >
    @endif
    Files
  </div>

  <div class="mb-2">
    @if ($group->getSetting('module_member', true) == true)
      <input type="checkbox" name="module_member" checked>
    @else
      <input type="checkbox" name="module_member" >
    @endif
    Members
  </div>

  <div class="mb-2">
    @if ($group->getSetting('module_map', true) == true)
      <input type="checkbox" name="module_map" checked>
    @else
      <input type="checkbox" name="module_map" >
    @endif
    Map
  </div>

  <div class="mb-2">
    Custom tab icon, should be fa-something (see <a href="https://fontawesome.com/v4.7.0/icons/" target="_blank">https://fontawesome.com/v4.7.0/icons/</a>)
    <br/>
    <input type="text" class="form-control" value="{{$group->getSetting('module_custom_icon')}}" name="module_custom_icon">
    Custom tab name (leave blank to disable completely):
    <br/>
    <input type="text" class="form-control" value="{{$group->getSetting('module_custom_name')}}" name="module_custom_name">
    Custom tab html :
    <br/>
    <textarea type="text" style="width:100%" class="form-control" name="module_custom_html">{!!$group->getSetting('module_custom_html')!!}</textarea>
  </div>







<div class="form-group mt-8">
  {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary']) !!}
</div>


{!! Form::close() !!}




@endsection
