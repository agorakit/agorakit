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



    <div class="form-group">
      {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary form-control']) !!}
    </div>


    {!! Form::close() !!}




@endsection
