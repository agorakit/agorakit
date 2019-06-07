@include ('partials.selectize')



@push('js')
  <script>
  $( document ).ready(function() {
    $('.tags').selectize({
      persist: true,
      create: true
    });
  });
  </script>
@endpush


<div class="form-group">
  <label for="tags">{{trans('messages.tags')}}</label>
  <select class="form-control tags" name="tags[]" multiple="multiple">
    @if (isset($model_tags))
      @foreach ($model_tags as $tag)
        <option selected="selected" value="{{$tag->name}}">{{$tag->name}}</option>
      @endforeach
    @endif

    @if (isset($group) && $group->exists)
      @foreach ($group->allowedTags() as $tag)
        <option value="{{$tag->name}}">{{$tag->name}}</option>
      @endforeach
    @endif
  </select>
  <span id="tagshelp" class="help-block">{{trans('messages.tags_help')}}</span>
</div>
