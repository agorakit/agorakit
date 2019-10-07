@extends('app')

@section('content')

@include('groups.tabs')
<div class="tab_content p-4">
  <h1>{{trans('messages.modify')}} <strong>"{{$discussion->name}}"</strong></h1>


  {!! Form::model($discussion, array('action' => ['DiscussionTagController@update', $discussion->group, $discussion], 'up-target' => '.tags', 'up-layer' => 'page')) !!}


      @foreach ($all_tags as $tag)
        <div class="form-check mb-1">
          <input class="form-check-input" type="checkbox" name="tags[]" value="{{$tag->normalized}}" id="{{$tag->normalized}}"
          @if ($discussion_tags->contains($tag)) checked="checked" @endif>
          <label class="form-check-label badge" for="{{$tag->normalized}}" style="background-color : {{$tag->color}}; color: white; font-size: 1rem">
            {{$tag->name}}
          </label>
        </div>
      @endforeach

  <div class="form-group">
    {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary mt-4']) !!}
  </div>


  {!! Form::close() !!}


</div>

@endsection
