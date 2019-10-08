@extends('dialog')

@section('content')



  {!! Form::model($discussion, array('action' => ['DiscussionTagController@update', $discussion->group, $discussion], 'up-target' => '.tags', 'up-layer' => 'page')) !!}


  @foreach ($all_tags as $tag)
    <div class="form-check mb-1">
      <input class="form-check-input" type="checkbox" name="tags[]" value="{{$tag->normalized}}" id="{{$tag->normalized}}"
      @if ($discussion_tags->contains($tag)) checked="checked"
      @endif>
      <label class="form-check-label badge" for="{{$tag->normalized}}" style="background-color : {{$tag->color}}; color: white; font-size: 1rem">
        {{$tag->name}}
      </label>
    </div>
  @endforeach


  <div class="mt-3">
    <div>
      {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary']) !!}
    </div>
  </div>




  {!! Form::close() !!}




@endsection
