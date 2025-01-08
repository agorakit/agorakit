@extends('group')

@section('content')

  <h1>{{ trans('group.features') }}</h1>

  <p>{{ trans('group.features_help') }}</p>


  {!! Form::open(array('action' => ['ModuleController@update', $group])) !!}





  <div class="mb-2">
    @if ($group->getSetting('module_discussion', true) == true)
      <input type="checkbox" id="module_discussion" name="module_discussion" checked>
    @else
      <input type="checkbox" id="module_discussion" name="module_discussion" >
    @endif
    {!! Form::label('module_discussion', trans('Discussions'), ['class' => 'humble']) !!}
  </div>

  <div class="mb-2">
    @if ($group->getSetting('module_action', true) == true)
      <input type="checkbox" id="module_action" name="module_action" checked>
    @else
      <input type="checkbox" id="module_action" name="module_action" >
    @endif
    {!! Form::label('module_action', trans('Calendar'), ['class' => 'humble']) !!}
  </div>


  <div class="mb-2">
    @if ($group->getSetting('module_file', true) == true)
      <input type="checkbox" id="module_file" name="module_file" checked>
    @else
      <input type="checkbox" id="module_file" name="module_file" >
    @endif
    {!! Form::label('module_file', trans('Files'), ['class' => 'humble']) !!}
  </div>

  <div class="mb-2">
    @if ($group->getSetting('module_member', true) == true)
      <input type="checkbox" id="module_member" name="module_member" checked>
    @else
      <input type="checkbox" id="module_member" name="module_member" >
    @endif
    {!! Form::label('module_member', trans('Members'), ['class' => 'humble']) !!}
  </div>

  <div class="mb-2">
    @if ($group->getSetting('module_map', true) == true)
      <input type="checkbox" id="module_map" name="module_map" checked>
    @else
      <input type="checkbox" id="module_map" name="module_map" >
    @endif
    {!! Form::label('module_map', trans('Map'), ['class' => 'humble']) !!}
  </div>

  <div class="mb-2">
    {!! Form::label('module_custom_icon', trans('Custom tab icon')) !!}
    <div class="small-help">
      should be fa-something (see <a href="https://fontawesome.com/v4.7.0/icons/" target="_blank">https://fontawesome.com/v4.7.0/icons/</a>)
    </div>
    <input type="text" class="form-control" value="{{$group->getSetting('module_custom_icon')}}" id="module_custom_icon" name="module_custom_icon">

    {!! Form::label('module_custom_name', trans('Custom tab name (leave blank to disable completely)')) !!}
    <input type="text" class="form-control" value="{{$group->getSetting('module_custom_name')}}" id="module_custom_name" name="module_custom_name">

    {!! Form::label('module_custom_html', trans('Custom tab HTML')) !!}
    <textarea type="text" style="width:100%" class="form-control" id="module_custom_html" name="module_custom_html">
      {!!$group->getSetting('module_custom_html')!!}
    </textarea>
  </div>







<div class="form-group mt-8">
  {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary']) !!}
</div>


{!! Form::close() !!}




@endsection
